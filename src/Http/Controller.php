<?php

namespace CrudSample\Http;

use CrudSample\Application;

abstract class Controller
{
    /**
     * Silex Application
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getDb()
    {
        return $this->app['db'];
    }

    public function getTwig()
    {
        return $this->app['twig'];
    }
}
