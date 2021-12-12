<?php

namespace App\Entity\Postfix;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table("postfix_alias_domain")]
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Domain
     */
    public function getOrigine(): Domain
    {
        return $this->origine;
    }

    /**
     * @param Domain $origine
     */
    public function setOrigine(Domain $origine): void
    {
        $this->origine = $origine;
    }

    /**
     * @return Domain
     */
    public function getDestination(): Domain
    {
        return $this->destination;
    }

    /**
     * @param Domain $destination
     */
    public function setDestination(Domain $destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateCreated(): \DateTimeInterface
    {
        return $this->date_created;
    }

    /**
     * @param \DateTimeInterface $date_created
     */
    public function setDateCreated(\DateTimeInterface $date_created): void
    {
        $this->date_created = $date_created;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateModified(): \DateTimeInterface
    {
        return $this->date_modified;
    }

    /**
     * @param \DateTimeInterface $date_modified
     */
    public function setDateModified(\DateTimeInterface $date_modified): void
    {
        $this->date_modified = $date_modified;
    }

    /**
     * @return bool
     */
    public function isIsActive(): bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     */
    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }

}