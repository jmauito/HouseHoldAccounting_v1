<?php
namespace Domain;

use Lib\DomainModel;

final class Store extends DomainModel{
    private $businessName;
    private $tradeName;
    private $ruc;
    private $parentAddress;
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getBusinessName() {
        return $this->businessName;
    }

    function getTradeName() {
        return $this->tradeName;
    }

    function getRuc() {
        return $this->ruc;
    }

    function getParentAddress() {
        return $this->parentAddress;
    }

    function setBusinessName($businessName): void {
        $this->businessName = $businessName;
    }

    function setTradeName($tradeName): void {
        if (null == $tradeName){
            $this->tradeName = $this->getBusinessName();
        }else {
            $this->tradeName = $tradeName;
        }  
    }

    function setRuc(string $ruc): void {
        if (strlen($ruc) !== 13){
            throw new \Exception("La longitud del ruc debe ser de 13 caracteres ('$ruc')");
        }
        $this->ruc = $ruc;
    }

    function setParentAddress($parentAddress): void {
        $this->parentAddress = $parentAddress;
    }

    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->businessName = $this->getBusinessName();
        $dto->tradeName = $this->getTradeName();
        $dto->ruc = $this->getRuc();
        $dto->parentAddress = $this->getParentAddress();
        return $dto;
    }
    
}