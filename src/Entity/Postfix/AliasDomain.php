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
    private \DateTimeInterface $date_created;
    private \DateTimeInterface $date_modified;
    private bool $is_active;
}