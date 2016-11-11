<?php

namespace CrudSample\Domain;

use DateTime;
use JsonSerializable;

class Profile implements JsonSerializable
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
        return in_array($this->id, [self::TYPE_ADMIN, self::TYPE_MANAGER]);
    }

    public function hasReadPermission()
    {
        return true;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'hasWritePermission' => $this->hasWritePermission(),
            'hasReadPermission' => $this->hasReadPermission()
        ];
    }
}
