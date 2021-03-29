<?php

namespace App\Entity;

use App\Repository\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomainRepository::class)
 */
class Domain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $domain;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_aliases;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_mailboxes;

    /**
     * @ORM\Column(type="bigint")
     */
    private $max_quota;

    /**
     * @ORM\Column(type="bigint")
     */
    private $quota;

    /**
     * @ORM\Column(type="boolean")
     */
    private $backup_mx;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $edition_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\OneToMany(targetEntity=Mailbox::class, mappedBy="domain")
     */
    private $mailboxes;

    public function __construct()
    {
        $this->mailboxes = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxAliases(): ?int
    {
        return $this->max_aliases;
    }

    public function setMaxAliases(int $max_aliases): self
    {
        $this->max_aliases = $max_aliases;

        return $this;
    }

    public function getMaxMailboxes(): ?int
    {
        return $this->max_mailboxes;
    }

    public function setMaxMailboxes(int $max_mailboxes): self
    {
        $this->max_mailboxes = $max_mailboxes;

        return $this;
    }

    public function getMaxQuota(): ?string
    {
        return $this->max_quota;
    }

    public function setMaxQuota(string $max_quota): self
    {
        $this->max_quota = $max_quota;

        return $this;
    }

    public function getQuota(): ?string
    {
        return $this->quota;
    }

    public function setQuota(string $quota): self
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getEditionDate(): ?\DateTimeInterface
    {
        return $this->edition_date;
    }

    public function setEditionDate(?\DateTimeInterface $edition_date): self
    {
        $this->edition_date = $edition_date;

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

    /**
     * @return Collection|Mailbox[]
     */
    public function getMailboxes(): Collection
    {
        return $this->mailboxes;
    }

    public function addMailbox(Mailbox $mailbox): self
    {
        if (!$this->mailboxes->contains($mailbox)) {
            $this->mailboxes[] = $mailbox;
            $mailbox->setDomain($this);
        }

        return $this;
    }

    public function removeMailbox(Mailbox $mailbox): self
    {
        if ($this->mailboxes->removeElement($mailbox)) {
            // set the owning side to null (unless already changed)
            if ($mailbox->getDomain() === $this) {
                $mailbox->setDomain(null);
            }
        }

        return $this;
    }
}
