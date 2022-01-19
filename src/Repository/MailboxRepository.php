<?php

namespace App\Repository;

use App\Entity\Postfix\Mailbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mailbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mailbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mailbox[]    findAll()
 * @method Mailbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailboxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mailbox::class);
    }
}