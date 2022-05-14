<?php

namespace Domain;

use Lib\DomainModel;

class BillAdditionalInformation extends DomainModel
{
    private $name;
    private $value;

    /**
     * @param $id int default = 0
     */
    public function __construct(int $id = 0)
    {
        parent::__construct($id = 0);
    }

    /**
     * @return string
     */
    public function getName():string
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
    public function getValue():string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->name = $this->getName();
        $dto->value = $this->getValue();

        return $dto;
    }



}