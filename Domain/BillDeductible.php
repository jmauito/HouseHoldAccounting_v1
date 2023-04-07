<?php
namespace Domain;

use Lib\DomainModel;

final class BillDeductible extends DomainModel{
    private $billId;
    private $deductible;
    private $value;
    private $deductibleId;
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getBillId(): int {
        return $this->billId;
    }

    function getDeductible(): Deductible {
        return $this->deductible;
    }

    function getValue():float {
        return $this->value;
    }
    
    function getDeductibleId():int{
        return $this->deductibleId;
    }

    function setBillId(int $billId): void {
        $this->billId = $billId;
    }
    
    function setDeductible(Deductible $deductible): void {
        $this->deductible = $deductible;
    }

    function setValue(float $value): void {
        $this->value = $value;
    }
    
    function setDeductibleId(int $deductibleId): void {
        $this->deductibleId = $deductibleId;
    }

    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->billId = $this->getBillId();
        $dto->deductible = $this->getDeductible();
        $dto->value = $this->getValue();
        return $dto;
    }
    
}