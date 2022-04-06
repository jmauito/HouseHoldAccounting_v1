<?php
namespace Domain;

use Lib\DomainModel;

final class BillDetail extends DomainModel{
    private $mainCode;
    private $description;
    private $quantity;
    private $unitPrice;
    private $discount;
    private $totalPriceWithoutTaxes;
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getMainCode(): string {
        return $this->mainCode;
    }

    function getDescription():string {
        return $this->description;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getUnitPrice() {
        return $this->unitPrice;
    }

    function getDiscount() {
        return $this->discount;
    }

    function getTotalPriceWithoutTaxes() {
        return $this->totalPriceWithoutTaxes;
    }

    function setMainCode(string $mainCode): void {
        $this->mainCode = $mainCode;
    }

    function setDescription(string $code): void {
        $this->description = $code;
    }

    function setQuantity(float $quantity): void {
        $this->quantity = $quantity;
    }

    function setUnitPrice(float $unitPrice): void {
        $this->unitPrice = $unitPrice;
    }

    function setDiscount(float $discount): void {
        $this->discount = $discount;
    }

    function setTotalPriceWithoutTaxes(float $totalPriceWithoutTaxes): void {
        $this->totalPriceWithoutTaxes = $totalPriceWithoutTaxes;
    }

    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->mainCode = $this->getMainCode();
        $dto->description = $this->getDescription();
        $dto->quantity = $this->getQuantity();
        $dto->unitPrice = $this->getUnitPrice();
        $dto->discount = $this->getDiscount();
        $dto->totalPriceWithoutTaxes = $this->getTotalPriceWithoutTaxes();
        
        return $dto;
    }
    
}