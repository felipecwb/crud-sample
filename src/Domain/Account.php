<?php

namespace CrudSample\Domain;

use Symfony\Component\HttpFoundation\Session\Session;

class Account
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getId()
    {
        return $this->session->getId();
    }

    public function isLogged()
    {
        return $this->session->get('logged');
    }

    public function login(User $user)
    {
        $this->session->set('user', $user);
        $this->session->set('logged', true);
    }

    public function logout()
    {
        $this->session->set('user', null);
        $this->session->set('logged', false);
    }

    public function getUser()
    {
        return $this->session->get('user');
    }
}
