<?php

namespace CrudSample;

use CrudSample\Domain\Account;
use Silex\Application as SilexApp;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class Application extends SilexApp
{
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->register(new SessionServiceProvider());
        $this->register(new ServiceControllerServiceProvider());
        $this->register(new DoctrineServiceProvider(), array(
            'db.options' => $this['database.info']
        ));
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => $this['directory.base'] . '/views',
        ));
        $this->registerJsonRequestBody();
        $this->registerRoutes();
    }

    private function registerJsonRequestBody()
    {
        $this->before(function (Request $request) {
            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : array());
            }
        });
    }

    public function addController($httpClass)
    {
        $alias = 'controller.' . str_replace('\\', '.', strtolower($httpClass));
        $httpClass = 'CrudSample\\Http\\' . $httpClass;

        $this[$alias] = function () use ($httpClass) {
            return new $httpClass($this);
        };
    }

    private function registerRoutes()
    {
        $this->addController('Home');
        $this->get('/', 'controller.home:index');

        $this->mount('/account', function ($login) {
            $this->addController('Account');

            $login->get('/', 'controller.account:index');
            $login->post('/login', 'controller.account:login');
            $login->post('/logout', 'controller.account:logout');
        });

        $this->mount('/backend', function ($backend) {
            // must be logged
            $backend->before(function () {
                $account = new Account($this['session']);

                if (! $account->isLogged()) {
                    return $this->redirect('/account');
                }
            });

            $this->addController('Backend');
            $backend->get('/', 'controller.backend:users');
        });

        $this->mount('/api', function ($api) {
            // must be logged
            $api->before(function () {
                $account = new Account($this['session']);

                if (! $account->isLogged()) {
                    return $this->json(['message' => 'Need be logged!'], 401);
                }
            });

            $this->addController('Api\\Profile');
            $api->get('/profile', 'controller.api.profile:getAll');
            $api->get('/profile/{id}', 'controller.api.profile:get');

            $this->addController('Api\\User');
            $api->get('/user', 'controller.api.user:getAll');
            $api->get('/user/{id}', 'controller.api.user:get');
            $api->post('/user', 'controller.api.user:create');
            $api->match('/user/{id}', 'controller.api.user:update')->method('PUT|PATCH');
            $api->delete('/user/{id}', 'controller.api.user:delete');
        });
    }
}
