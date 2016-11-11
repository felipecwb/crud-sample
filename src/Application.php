<?php

namespace CrudSample;

use Silex\Application as SilexApp;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
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

            $login->get('/', 'controller.login:index');
            $login->post('/login', 'controller.account:login');
            $login->post('/logout', 'controller.account:logout');
        });
    }
}
