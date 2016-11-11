<?php

namespace CrudSample\Storage;

use Doctrine\DBAL\Connection;

abstract class Database
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * exchange the tabla data to class data
     * @param  array  $data columns
     */
    protected abstract function exchange(array $data = array());
}
