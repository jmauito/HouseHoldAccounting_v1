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
    private $billDetailDeductible;
    private $billDetailExpense;
    
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

    function getBillDetailDeductible():?BillDetailDeductible {
        return $this->billDetailDeductible;
    }
    
    function getBillDetailExpense():?BillDetailExpense {
        return $this->billDetailExpense;
    }

    function setMainCode(string $mainCode): void {
        $mainCode = str_replace([' ','.'], '_', $mainCode);
        $this->mainCode = str_replace(' ', '_', $mainCode);
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

    function setBillDetailDeductible(BillDetailDeductible $billDetailDeductible){
        $this->billDetailDeductible = $billDetailDeductible;
    }

    function setBillDetailExpense(BillDetailExpense $billDetailExpense){
        $this->billDetailExpense = $billDetailExpense;
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
        if ($this->billDetailDeductible !== null){
            $dto->billDetailDeductible = $this->getBillDetailDeductible()->toDto();
        }
        if ($this->billDetailExpense !== null){
            $dto->billDetailExpense = $this->getBillDetailExpense()->toDto();
        }


        return $dto;
    }
    
}