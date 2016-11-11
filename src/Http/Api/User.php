<?php

namespace CrudSample\Http\Api;

use DateTime;
use InvalidArgumentException;
use CrudSample\Http\Controller;
use CrudSample\Domain\Account;
use CrudSample\Domain\User as UserEntity;
use CrudSample\Storage\User as Storage;
use CrudSample\Storage\Profile as ProfileStorage;
use Symfony\Component\HttpFoundation\Request;

class User extends Controller
{
    public function getAll(Request $request)
    {
        $storage = new Storage($this->getDb());
        return $this->app->json($storage->find(), 200);
    }

    public function get($id)
    {
        $storage = new Storage($this->getDb());
        $user = $storage->findById($id);

        if (! $user) {
            return $this->app->json(['status' => 1, 'message' => 'Not Found'], 404);
        }

        return $this->app->json($user, 200);
    }

    public function create(Request $request)
    {
        if (! $this->canWrite()) {
            return $this->app->json(['status' => 5, 'message' => 'Sem Permissão'], 401);
        }

        try {
            $user = new UserEntity();
            $user->setName($request->get('name'))
                ->setEmail($request->get('email'))
                ->setPassword($request->get('password'));

            $birthDate = $request->get('birthDate');
            if ($birthDate) {
                $user->setBirthDate(new DateTime($birthDate));
            }
        } catch (InvalidArgumentException $e) {
            return $this->app->json(['status' => 4, 'message' => 'Dados Invalidos'], 400);
        }

        $profileStorage = new ProfileStorage($this->getDb());
        $profile = $profileStorage->findById($request->get('profile'));

        if (! $profile) {
            return $this->app->json(['status' => 2, 'message' => 'Perfil desconhecido'], 400);
        }

        $user->setProfile($profile);

        $storage = new Storage($this->getDb());
        if (! $id = $storage->create($user)) {
            return $this->app->json(['status' => 1, 'message' => 'Problemas para criar'], 500);
        }

        return $this->app->json(['status' => 0, 'message' => 'Criado', 'id' => $id], 201);
    }

    public function update($id, Request $request)
    {
        if (! $this->canWrite()) {
            return $this->app->json(['status' => 5, 'message' => 'Sem Permissão'], 401);
        }

        $storage = new Storage($this->getDb());
        $user = $storage->findById($id);

        if (! $user) {
            return $this->app->json(['status' => 1, 'message' => 'Not Found'], 404);
        }

        $user->setName($request->get('name', $user->getName()));
        $user->setEmail($request->get('email', $user->getEmail()));
        $user->setPassword($request->get('password', $user->getPassword()));
        $birthDate = $request->get('birthDate');
        if ($birthDate) {
            $user->setBirthDate(new DateTime($birthDate));
        }

        $profileId = $request->get('profile');
        if ($profileId != $user->getProfile()->getId()) {
            $storage = new ProfileStorage($this->getDb());
            $profile = $storage->findById($profileId);

            if (! $profile) {
                return $this->app->json(['status' => 3, 'message' => 'Perfil desconhecido'], 400);
            }

            $user->setProfile($profile);
        }

        if (! $storage->update($user)) {
            return $this->app->json(['status' => 2, 'message' => 'Problemas para deletar'], 500);
        }

        return $this->app->json(['status' => 0, 'message' => 'Atualizado'], 200);
    }

    public function delete($id)
    {
        if (! $this->canWrite()) {
            return $this->app->json(['status' => 5, 'message' => 'Sem Permissão'], 401);
        }

        $storage = new Storage($this->getDb());
        $user = $storage->findById($id);

        if (! $user) {
            return $this->app->json(['status' => 1, 'message' => 'Not Found'], 404);
        }

        if (! $storage->remove($user)) {
            return $this->app->json(['status' => 2, 'message' => 'Problemas para deletar'], 500);
        }

        return $this->app->json(['status' => 0, 'message' => 'Deletado'], 200);
    }

    protected function canWrite()
    {
        $account = new Account($this->app['session']);
        return $account->getUser()
                        ->getProfile()
                        ->hasWritePermission();
    }
}
