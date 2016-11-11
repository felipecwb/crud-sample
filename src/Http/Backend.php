<?php

namespace CrudSample\Http;

use CrudSample\Domain\Account;

class Backend extends Controller
{
    public function users()
    {
        $account = new Account($this->app['session']);

        return $this->getTwig()
            ->render(
                'backend/users.html',
                ['user_json' => json_encode($account->getUser())]
            );
    }
}
