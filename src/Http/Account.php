<?php

namespace CrudSample\Http;

use CrudSample\Domain\Account as AccountDomain;
use CrudSample\Storage\User;
use Symfony\Component\HttpFoundation\Request;

class Account extends Controller
{
    public function index()
    {
        return $this->getTwig()->render('login.html');
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->app->json(['status' => 1, 'message' => 'Invalid Email!'], 400);
        }

        $storage = new User($this->getDb());
        $user = $storage->find(['email' => $email]);

        if (empty($user)) {
            return $this->app->json(['status' => 2, 'message' => 'Email does\'t exists!'], 401);
        }

        // first row
        $user = $user[0];

        $password = $request->get('password');
        if ($user->getPassword() !== $password) {
            return $this->app->json(['status' => 3, 'message' => 'Incorrect Password!'], 401);
        }

        $domain = new AccountDomain($this->app['session']);
        $domain->login($user);

        return $this->app->json([
            'status' => 0, 'message' => 'success', 'hash' => $domain->getId()
        ], 200);
    }

    public function logout(Request $request)
    {
        $domain = new AccountDomain($this->app['session']);

        if (! $domain->isLogged()) {
            return $this->app->json(['status' => 1, 'message' => 'No one is logged!'], 412);
        }

        $domain->logout();

        return $this->app->json(['status' => 0, 'message' => 'Kick Out!'], 200);
    }
}
