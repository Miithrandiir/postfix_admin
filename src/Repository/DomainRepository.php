<?php

namespace App\Repository;

use App\Entity\Postfix\Domain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class DomainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Domain::class);
    }

    public function findDomainByIdQueryBuilder(int $user_id): QueryBuilder
    {
        return $this->createQueryBuilder('u')->where('u.user = :id')->setParameter('id', $user_id);
    }
}