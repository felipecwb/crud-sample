<?php

namespace CrudSample\Http\Api;

use CrudSample\Http\Controller;
use CrudSample\Storage\Profile as Storage;
use Symfony\Component\HttpFoundation\Request;

class Profile extends Controller
{
    public function getAll(Request $request)
    {
        $storage = new Storage($this->getDb());
        return $this->app->json($storage->find(), 200);
    }

    public function get($id)
    {
        $storage = new Storage($this->getDb());
        $profile = $storage->findById($id);

        if (! $profile) {
            return $this->app->json(['message' => 'Not Found'], 404);
        }

        return $this->app->json($profile, 200);
    }
}
