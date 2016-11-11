<?php

namespace CrudSample\Http;

use Symfony\Component\HttpFoundation\Request;

class Account extends Controller
{
    public function index()
    {
        $this->app->redirect('/');
    }

    public function login(Request $request)
    {
        return $this->app->json(['message' => 'Not Implemented!'], 501);
    }

    public function logout(Request $request)
    {
        return $this->app->json(['message' => 'Not Implemented!'], 501);
    }
}
