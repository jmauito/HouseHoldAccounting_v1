<?php
namespace Domain;

use Lib\DomainModel;

final class Deductible extends DomainModel{
    private $name;
    function getName() {
        return $this->name;
    }

    function setName($name): void {
        $this->name = $name;
    }

    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    public function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->name = $this->getName();
        $dto->active = $this->isActive();
        return $dto;
    }
    
}