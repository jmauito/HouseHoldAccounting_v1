<?php

namespace Domain;

use Lib\DomainModel;

class Expense extends DomainModel
{
    private $name;

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param sting $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function toDto()
    {
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->name = $this->getName();
        return $dto;
    }
}