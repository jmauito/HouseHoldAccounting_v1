<?php
namespace Domain;

use Lib\DomainModel;

final class BillDeductible extends DomainModel{
    private $deductible;
    private $value;
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getDeductible(): Deductible {
        return $this->deductible;
    }

    function getValue():float {
        return $this->value;
    }

    function setDeductible(Deductible $deductible): void {
        $this->deductible = $deductible;
    }

    function setValue(float $value): void {
        $this->value = $value;
    }

    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->deductible = $this->getName();
        $dto->value = $this->getValue();
        return $dto;
    }
    
}