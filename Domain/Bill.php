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
    private $billDeductibles = [];
    private $billAdditionalInformation = [];
    private $billExpenses = [];
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getAccessKey(): string {
        return $this->accessKey;
    }

    function getEstablishment():string {
        return $this->establishment;
    }

    function getEmissionPoint():string {
        return $this->emissionPoint;
    }

    function getSecuential():string {
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
    
    function getBillDeductibles(): array{
        return $this->billDeductibles;
    }

    function getBillAdditionalInformation(): array{
        return $this->billAdditionalInformation;
    }

    function getBillExpenses(): array{
        return $this->billExpenses;
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
    
    function addBillDeductible(BillDeductible $billDeductible): ?bool{
        if (TRUE === $index = array_search($billDeductible, $this->getBillDeductibles()) ){
            return null;
        }
        $this->billDeductibles[] = $billDeductible;
        return true;
    }
    
    function deleteBillDeductibles(BillDeductible $billDeductible): ?bool{
        if (FALSE === $index = array_search($billDeductible, $this->billDeductibles) ){
            return null;
        }
        $billDeductibles = [];
        foreach ($this->billDeductibles as $key=>$value){
            if ($key !== $index){
                $billDeductibles[] = $value;
            }
        }
        $this->billDeductibles = $billDeductibles;
        return true;
    }

    function addBillAdditionalInformation(BillAdditionalInformation $billAdditionalInformation): ?bool{
        if (TRUE === $index = array_search($billAdditionalInformation, $this->getBillAdditionalInformation()) ){
            return null;
        }
        $this->billAdditionalInformation[] = $billAdditionalInformation;
        return true;
    }

    function deleteBillAdditionalInformation(BillAdditionalInformation $billAdditionalInformation): ?bool{
        if (FALSE === $index = array_search( $billAdditionalInformation, $this->getBillAdditionalInformation() ) ){
            return null;
        }
        $listBillAdditionalInformation = [];
        foreach ($this->billAdditionalInformation as $key=>$value){
            if ($key !== $index){
                $listBillAdditionalInformation[] = $value;
            }
        }
        $this->billAdditionalInformation = $listBillAdditionalInformation;
        return true;
    }

    function addBillExpense(BillExpense $billExpense): ?bool{
        if (TRUE === $index = array_search($billExpense, $this->getBillExpenses()) ){
            return null;
        }
        $this->billExpenses[] = $billExpense;
        return true;
    }

    function deleteBillExpenses(BillExpense $billExpense): ?bool{
        if (FALSE === $index = array_search( $billExpense, $this->getBillExpenses() ) ){
            return null;
        }
        $billExpenses = [];
        foreach ($this->billExpenses as $key=>$value){
            if ($key !== $index){
                $billExpenses[] = $value;
            }
        }
        $this->billExpenses = $billExpenses;
        return true;
    }
    
    function toDto():\stdClass
    {
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
        $dto->voucherType = $this->voucherType->toDto();
        $dto->buyer = $this->buyer->toDto();
        $dto->billDetails = [];
        foreach ($this->billDetails as $billDetail){
            $dto->billDetails[] = $billDetail->toDto();
        }
        $dto->billAdditionalInformation = [];
        foreach ($this->billAdditionalInformation as $billAdditionalInformation){
            $dto->billAdditionalInformation[] = $billAdditionalInformation->toDto();
        }
        $dto->billExpenses = [];
        foreach ($this->billExpenses as $billExpense){
            $dto->billExpenses[] = $billExpense->toDto();
        }
        return $dto;
    }
    
    function toJson(){
        return json_encode($this->toDto());
    }
    
}