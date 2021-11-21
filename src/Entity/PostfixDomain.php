<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity()]
class PostfixDomain
{
    #[Id, GeneratedValue, Column(type: Types::BIGINT)]
    public int $id;

    #[Column(type: Types::STRING, unique: true)]
    public string $domain;

    #[Column(type: Types::STRING)]
    public string $description;

    #[Column(type: Types::INTEGER)]
    public int $nb_aliases;

    #[Column(type: Types::INTEGER)]
    public int $nb_mailboxes;

    #[Column(name: 'maxquota', type: Types::INTEGER)]
    public int $max_quota;

    #[Column(type: Types::INTEGER)]
    public int $quota;

    #[Column(name: 'backupmx',type: Types::BOOLEAN)]
    public bool $backup_mx;

    #[Column(type: Types::DATETIME_MUTABLE)]
    public DateTimeInterface $date_create;

    #[Column(type: Types::DATETIME_MUTABLE)]
    public DateTimeInterface $date_modified;

    #[Column(type: Types::BOOLEAN)]
    public bool $is_active;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbAliases(): ?int
    {
        return $this->nb_aliases;
    }

    public function setNbAliases(int $nb_aliases): self
    {
        $this->nb_aliases = $nb_aliases;

        return $this;
    }

    public function getNbMailboxes(): ?int
    {
        return $this->nb_mailboxes;
    }

    public function setNbMailboxes(int $nb_mailboxes): self
    {
        $this->nb_mailboxes = $nb_mailboxes;

        return $this;
    }

    public function getMaxQuota(): ?int
    {
        return $this->max_quota;
    }

    public function setMaxQuota(int $max_quota): self
    {
        $this->max_quota = $max_quota;

        return $this;
    }

    public function getQuota(): ?int
    {
        return $this->quota;
    }

    public function setQuota(int $quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    public function getBackupMx(): ?bool
    {
        return $this->backup_mx;
    }

    public function setBackupMx(bool $backup_mx): self
    {
        $this->backup_mx = $backup_mx;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(\DateTimeInterface $date_modified): self
    {
        $this->date_modified = $date_modified;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }
}