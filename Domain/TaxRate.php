<?php
namespace Domain;

use Lib\DomainModel;

final class TaxRate extends DomainModel {
    private $taxId;
    private $name;
    private $code;

    public function getTaxId() : int {
        return $this->taxId;
    }
    
    public function getName() : string {
        return $this->name;
    }

    public function getCode() : string {
        return $this->code;
    }

    public function setTaxId(int $taxId): void {
        $this->taxId = $taxId;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setCode(string $code): void {
        $this->code = $code;
    }

    public function toDto():\stdClass{
        $obj = new \stdClass();
        $obj->id = $this->getId();
        $obj->taxId = $this->getTaxId();
        $obj->name = $this->getName();
        $obj->code = $this->getCode();
        return $obj;

    }

    public function toJson(){
        return json_encode($this->toDto());
    }
}