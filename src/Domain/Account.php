<?php

namespace CrudSample\Domain;

use CrudSample\Storage\User;
use Symfony\Component\HttpFoundation\Session\Session;

class Account
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var User
     */
    private $storage;

    /**
     * @param Session $session
     */
    public function __construct(Session $session, User $storage)
    {
        $this->session = $session;
        $this->storage = $storage;
    }

    public function isLogged()
    {
        return $this->session->get('logged');
    }

    public function login()
    {

    }

    public function logout()
    {

    }

    public function getUser()
    {
        return $this->session->get('user');
    }
}
