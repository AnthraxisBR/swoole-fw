<?php

namespace database\entity;


use Doctrine\ORM\Mapping as ORM;
use AnthraxisBR\SwooleFW\database\Entities;
use AnthraxisBR\SwooleFW\database\Entitites;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users extends Entities
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    public $id;

    /** @ORM\Column(type="string",length=250) */
    public $name;

    /** @ORM\Column(type="string",length=250) */
    public $password;

    /** @ORM\Column(type="string", length=250) */
    public $email = null;

    public function getName() { return $this->name; }
    public function getPassword() { return $this->password; }
    public function getEmail() { return $this->email; }

    public function __toString()
    {
        return json_encode($this);
    }
}
