<?php

use CrudSample\Application;

require_once __DIR__ . '/../vendor/autoload.php';

return new Application([
    'debug' => true,
    // database
    'database.info' => [
        'host' => '0.0.0.0',
        'post' => 5432,
        'user' => 'postgres',
        'password' => 'pass',
        'dbname' => 'postgres',
        'driver' => 'pdo_pgsql',
    ]
]);
