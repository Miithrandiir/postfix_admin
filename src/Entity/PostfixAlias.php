<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity()]
class PostfixAlias
{
    #[Id, GeneratedValue, Column(type: Types::BIGINT)]
    public int $id;

    public function getId(): ?string
    {
        return $this->id;
    }

}