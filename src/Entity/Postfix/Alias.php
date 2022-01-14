<?php

declare(strict_types=1);

namespace App\Entity\Postfix;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('postfix_alias')]
class Alias
{
    #[Id, GeneratedValue, Column(type: Types::BIGINT)]
    private int $id;

    #[ManyToOne(targetEntity: Domain::class, inversedBy: 'aliases')]
    private Domain $domain;

    #[Column(name: 'adress', type: Types::STRING)]
    private string $address;

    #[Column(type: Types::STRING)]
    private string $goto;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $date_created;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $date_modified;

    #[Column(name: 'active', type: Types::BOOLEAN)]
    private bool $is_active;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain): void
    {
        $this->domain = $domain;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getGoto(): string
    {
        return $this->goto;
    }

    public function setGoto(string $goto): void
    {
        $this->goto = $goto;
    }

    public function getDateCreated(): \DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): void
    {
        $this->date_created = $date_created;
    }

    public function getDateModified(): \DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(\DateTimeInterface $date_modified): void
    {
        $this->date_modified = $date_modified;
    }

    public function isIsActive(): bool
    {
        return $this->is_active;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }
}
