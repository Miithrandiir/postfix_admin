<?php

namespace App\Entity;
use App\Entity\Postfix\Domain;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class User implements UserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::STRING), Assert\Email]
    private string $username;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\OneToMany(targetEntity: Domain::class, mappedBy: 'user')]
    private Collection $domains;

    /**
     * @return Collection
     */
    public function getDomains(): Collection
    {
        return $this->domains;
    }

    /**
     * @param Collection $domains
     */
    public function setDomains(Collection $domains): void
    {
        $this->domains = $domains;
    }


    public function getId(): int
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