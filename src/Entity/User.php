<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Postfix\Domain;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::STRING), Assert\Email]
    private string $username;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Domain::class)]
    private Collection $domains;

    public function __construct()
    {
        $this->domains = new ArrayCollection();
    }

    public function getDomains(): Collection
    {
        return $this->domains;
    }

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
        return (string) $this->username;
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

    public function getRoles(): array
    {
        if (\count($this->roles) === 0 || !\in_array('ROLE_USER', $this->roles, true)) {
            $this->roles[] = 'ROLE_USER';
        }

        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt(): ?string
    {
        return null;
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function addDomain(Domain $domain): self
    {
        if (!$this->domains->contains($domain)) {
            $this->domains[] = $domain;
            $domain->setUser($this);
        }

        return $this;
    }

    public function removeDomain(Domain $domain): self
    {
        if ($this->domains->removeElement($domain)) {
            // set the owning side to null (unless already changed)
            if ($domain->getUser() === $this) {
                $domain->setUser(null);
            }
        }

        return $this;
    }
}
