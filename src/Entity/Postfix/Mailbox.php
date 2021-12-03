<?php

namespace App\Entity\Postfix;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table("postfix_mailbox")]
class Mailbox
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Domain::class,inversedBy: "mailboxes")]
    private Domain $domain;

    #[ORM\Column(type:Types::STRING)]
    private string $username;

    #[ORM\Column(type:Types::STRING)]
    private string $password;

    #[ORM\Column(type:Types::STRING)]
    private string $name;

    #[ORM\Column(type:Types::STRING)]
    private string $firstname;

    #[ORM\Column(name: "maildir",type: Types::STRING)]
    private string $mail_dir;

    #[ORM\Column(type:Types::BIGINT)]
    private int $quota;

    #[ORM\Column(type:Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $date_created;

    #[ORM\Column(type:Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $date_modified;

    #[ORM\Column(type:Types::BOOLEAN)]
    private bool $active;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Domain
     */
    public function getDomain(): Domain
    {
        return $this->domain;
    }

    /**
     * @param Domain $domain
     */
    public function setDomain(Domain $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getMailDir(): string
    {
        return $this->mail_dir;
    }

    /**
     * @param string $mail_dir
     */
    public function setMailDir(string $mail_dir): void
    {
        $this->mail_dir = $mail_dir;
    }

    /**
     * @return int
     */
    public function getQuota(): int
    {
        return $this->quota;
    }

    /**
     * @param int $quota
     */
    public function setQuota(int $quota): void
    {
        $this->quota = $quota;
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
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

}