<?php

namespace CrudSample\Http;

class Home extends Controller
{
    public function index()
    {
        echo '<pre>';

        $storage = new \CrudSample\Storage\User($this->app['db']);
        var_dump(
            $storage->find(['name' => 'F', 'email' => 'felipe.cwb@hotmail.com'])
        );

        return '';
    }
}
