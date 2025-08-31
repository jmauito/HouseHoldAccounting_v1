<?php
namespace Domain;

use Lib\DomainModel;

final class BillTaxRate extends DomainModel {
    private $billId;
    private $taxRateId;
    private $taxBase;
    private $value;

    public function getBillId() : int {
        return $this->billId;
    }
    
    public function getTaxRateId() : int {
        return $this->taxRateId;
    }

    public function getTaxBase() : float {
        return $this->taxBase;
    }

    public function getValue() : float {
        return $this->value;
    }

    public function setBillId(int $billId): void {
        $this->billId = $billId;
    }

    public function setTaxRateId(int $taxRateId): void {
        $this->taxRateId = $taxRateId;
    }

    public function setTaxBase(float $taxBase): void {
        $this->taxBase = $taxBase;
    }

    public function setValue(float $value): void {
        $this->value = $value;
    }

    public function toDto():\stdClass{
        $obj = new \stdClass();
        $obj->id = $this->getId();
        $obj->billId = $this->getBillId();
        $obj->taxRateId = $this->getTaxRateId();
        $obj->taxBase = $this->getTaxBase();
        $obj->value = $this->getValue();
        return $obj;

    }

    public function toJson(){
        return json_encode($this->toDto());
    }
}