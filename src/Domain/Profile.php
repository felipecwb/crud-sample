<?php

namespace CrudSample\Domain;

use DateTime;

class Profile
{
    const TYPE_ADMIN = 1;
    const TYPE_MANAGER = 2;
    const TYPE_USER = 3;

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $type;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = filter_var($name, FILTER_SANITIZE_STRING);
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function hasWritePermission()
    {
        return in_array($this->id, [TYPE_ADMIN, TYPE_MANAGER]);
    }

    public function hasReadPermission()
    {
        return true;
    }
}
