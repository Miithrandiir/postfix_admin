<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity()]
class User implements UserInterface
{
    #[Id, GeneratedValue, Column(type: Types::BIGINT)]
    public int $id;

    #[Column(type: Types::STRING), Assert\Email]
    public string $username;

    #[Column(type: Types::STRING)]
    public string $password;

    #[Column(type: Types::JSON)]
    public array $roles = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        if(sizeof($this->roles) == 0)
            $this->roles[0] = 'ROLE_USER';
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier() : string
    {
        return $this->username;
    }
}