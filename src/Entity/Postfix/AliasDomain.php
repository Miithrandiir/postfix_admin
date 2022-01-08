<?php

declare(strict_types=1);

namespace App\Entity\Postfix;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table('postfix_alias_domain')]
class AliasDomain
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::BIGINT)]
    private int $id;
    #[ORM\ManyToOne(targetEntity: Domain::class, inversedBy: 'origineAlias'), ORM\JoinColumn(name: 'domain_origin_id', referencedColumnName: 'id')]
    private Domain $origine;
    #[ORM\ManyToOne(targetEntity: Domain::class, inversedBy: 'destinationAlias'), ORM\JoinColumn(name: 'domain_target_id', referencedColumnName: 'id')]
    private Domain $destination;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $date_created;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $date_modified;
    #[ORM\Column(type: Types::BOOLEAN, name: 'active')]
    private bool $is_active;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOrigine(): Domain
    {
        return $this->origine;
    }

    public function setOrigine(Domain $origine): void
    {
        $this->origine = $origine;
    }

    public function getDestination(): Domain
    {
        return $this->destination;
    }

    public function setDestination(Domain $destination): void
    {
        $this->destination = $destination;
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

    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }
}
