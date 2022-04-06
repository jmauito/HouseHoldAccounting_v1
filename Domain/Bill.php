<?php
namespace Domain;

use Lib\DomainModel;

final class Bill extends DomainModel{
    private $accessKey;
    private $establishment;
    private $emissionPoint;
    private $secuential;
    private $dateOfIssue;
    private $establishmentAddress;
    private $totalWithoutTax;
    private $totalDiscount;
    private $tip;
    private $total;
    private $filePath;
    private $store;
    private $voucherType;
    private $buyer;
    private $billDetails = [];
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getAccessKey() {
        return $this->accessKey;
    }

    function getEstablishment() {
        return $this->establishment;
    }

    function getEmissionPoint() {
        return $this->emissionPoint;
    }

    function getSecuential() {
        return $this->secuential;
    }

    function getDateOfIssue() {
        return $this->dateOfIssue;
    }

    function getEstablishmentAddress() {
        return $this->establishmentAddress;
    }

    function getTotalWithoutTax() {
        return $this->totalWithoutTax;
    }

    function getTotalDiscount() {
        return $this->totalDiscount;
    }

    function getTip() {
        return $this->tip;
    }

    function getTotal() {
        return $this->total;
    }

    function getFilePath() {
        return $this->filePath;
    }
    
    function getStore(): Store {
        return $this->store;
    }
    
    function getVoucherType(): VoucherType {
        return $this->voucherType;
    }
    
    function getBuyer(): Buyer {
        return $this->buyer;
    }

    function getBillDetails(): array{
        return $this->billDetails;
    }
        
    function setAccessKey($accessKey): void {
        $this->accessKey = $accessKey;
    }

    function setEstablishment($establishment): void {
        $this->establishment = $establishment;
    }

    function setEmissionPoint($emissionPoint): void {
        $this->emissionPoint = $emissionPoint;
    }

    function setSecuential($secuential): void {
        $this->secuential = $secuential;
    }

    function setDateOfIssue($dateOfIssue): void {
        $this->dateOfIssue = $dateOfIssue;
    }

    function setEstablishmentAddress($establishmentAddress): void {
        $this->establishmentAddress = $establishmentAddress;
    }

    function setTotalWithoutTax($totalWithoutTax): void {
        $this->totalWithoutTax = $totalWithoutTax;
    }

    function setTotalDiscount($totalDiscount): void {
        $this->totalDiscount = $totalDiscount;
    }

    function setTip($tip): void {
        $this->tip = $tip;
    }

    function setTotal($total): void {
        $this->total = $total;
    }

    function setFilePath($filePath): void {
        $this->filePath = $filePath;
    }
    
    function setStore(Store $store):void{
        $this->store = $store;
    }
    
    function setVoucherType(VoucherType $voucherType): void {
        $this->voucherType = $voucherType;
    }
    
    function setBuyer(Buyer $buyer): void {
        $this->buyer = $buyer;
    }
    
    function addBillDetail(BillDetail $billDetail): ?bool{
        if (TRUE === $index = array_search($billDetail, $this->getBillDetails()) ){
            return null;
        }
        $this->billDetails[] = $billDetail;
        return true;
    }
    
    function deleteBillDetail(BillDetail $billDetail): ?bool{
        if (FALSE === $index = array_search($billDetail, $this->billDetails) ){
            return null;
        }
        $billDetails = [];
        foreach ($this->billDetails as $key=>$value){
            if ($key !== $index){
                $billDetails[] = $value;
            }
        }
        $this->billDetails = $billDetails;
        return true;
    }
    
    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->accessKey = $this->getAccessKey();
        $dto->establishment = $this->getEstablishment();
        $dto->emissionPoint = $this->getEmissionPoint();
        $dto->secuential = $this->getSecuential();
        $dto->dateOfIssue = $this->getDateOfIssue();
        $dto->establishmentAddress = $this->getEstablishmentAddress();
        $dto->totalWithoutTax = $this->getTotalWithoutTax();
        $dto->totalDiscount = $this->getTotalDiscount();
        $dto->tip = $this->getTip();
        $dto->total = $this->getTotal();
        $dto->filePath = $this->getFilePath();
        $dto->store = $this->store->toDto();
        $dto->voucherType = $this->voucherType->toDto;
        $dto->buyer = $this->buyer->toDto;
        return $dto;
    }
    
}