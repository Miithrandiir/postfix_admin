<?php

declare(strict_types=1);

namespace App\Entity\Postfix;

use App\Entity\User;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity, ORM\Table('postfix_domain')]
#[UniqueEntity('domain')]
class Domain
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $domain;

    #[ORM\Column(type: Types::STRING)]
    private string $description;

    #[ORM\Column(type: Types::INTEGER)]
    private int $nb_aliases;

    #[ORM\Column(type: Types::INTEGER)]
    private int $nb_mailboxes;

    #[ORM\Column(name: 'maxquota', type: Types::INTEGER)]
    private int $max_quota;

    #[ORM\Column(type: Types::INTEGER)]
    private int $quota = 0;

    #[ORM\Column(name: 'backupmx', type: Types::BOOLEAN)]
    private bool $backup_mx;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $date_created;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $date_modified;

    #[ORM\Column(name: 'active', type: Types::BOOLEAN)]
    private bool $is_active;

    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: Mailbox::class)]
    private Collection $mailboxes;

    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: Alias::class)]
    private Collection $aliases;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'domains')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'origine', targetEntity: AliasDomain::class)]
    private Collection $origineAlias;

    #[ORM\OneToMany(mappedBy: 'destination', targetEntity: AliasDomain::class)]
    private Collection $destinationAlias;

    public function __construct()
    {
        $this->mailboxes = new ArrayCollection();
        $this->aliases = new ArrayCollection();
        $this->origineAlias = new ArrayCollection();
        $this->destinationAlias = new ArrayCollection();
    }

    public function getDateCreated(): DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(DateTimeInterface $date_created): void
    {
        $this->date_created = $date_created;
    }

    public function getMailboxes(): Collection
    {
        return $this->mailboxes;
    }

    public function setMailboxes(Collection $mailboxes): void
    {
        $this->mailboxes = $mailboxes;
    }

    public function getId(): int
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

    public function getDateCreate(): ?DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreate(DateTimeInterface $date_create): self
    {
        $this->date_created = $date_create;

        return $this;
    }

    public function getDateModified(): ?DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(DateTimeInterface $date_modified): self
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

    public function getAliases(): Collection
    {
        return $this->aliases;
    }

    public function setAliases(Collection $aliases): void
    {
        $this->aliases = $aliases;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getOrigineAlias(): ArrayCollection|Collection
    {
        return $this->origineAlias;
    }

    /**
     * @param ArrayCollection|Collection $origineAlias
     */
    public function setOrigineAlias(ArrayCollection|Collection $origineAlias): void
    {
        $this->origineAlias = $origineAlias;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getDestinationAlias(): ArrayCollection|Collection
    {
        return $this->destinationAlias;
    }

    /**
     * @param ArrayCollection|Collection $destinationAlias
     */
    public function setDestinationAlias(ArrayCollection|Collection $destinationAlias): void
    {
        $this->destinationAlias = $destinationAlias;
    }

}
