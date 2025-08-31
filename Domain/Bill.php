<?php
namespace Domain;

use Lib\DomainModel;

final class Bill extends DomainModel{
    private $accessKey;
    private $establishment;
    private $emissionPoint;
    private $sequential;
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
    private $billTaxRates = [];
    private $licensePlate;
    
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

    function getSequential():string {
        return $this->sequential;
    }

    function getDateOfIssue(): \DateTime {
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

    function getBillTaxRates(): array{
        return $this->billTaxRates;
    }

    function getLicensePlate(): ?string {
        return $this->licensePlate;
    }
    
    function setAccessKey(string $accessKey): void {
        $this->accessKey = $accessKey;
    }

    function setEstablishment(string $establishment): void {
        $this->establishment = $establishment;
    }

    function setEmissionPoint(string $emissionPoint): void {
        $this->emissionPoint = $emissionPoint;
    }

    function setSequential(string $sequential): void {
        $this->sequential = str_pad($sequential,9,'0', STR_PAD_LEFT);
    }

    function setDateOfIssue(\DateTime $dateOfIssue): void {
        $this->dateOfIssue = $dateOfIssue;
    }

    function setEstablishmentAddress(?string $establishmentAddress): void {
        $this->establishmentAddress = $establishmentAddress;
    }

    function setTotalWithoutTax(float $totalWithoutTax): void {
        $this->totalWithoutTax = $totalWithoutTax;
    }

    function setTotalDiscount(float $totalDiscount): void {
        $this->totalDiscount = $totalDiscount;
    }

    function setTip(float $tip): void {
        $this->tip = $tip;
    }

    function setTotal(float $total): void {
        $this->total = $total;
    }

    function setFilePath(?string $filePath): void {
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

    function setLicensePlate(?string $licensePlate): void {
        if (NULL !== $licensePlate) $this->licensePlate = $licensePlate;
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

    function addBillTaxRate(BillTaxRate $billTaxRate): ?bool{
        if (TRUE === $index = array_search($billTaxRate, $this->getBillTaxRates()) ){
            return null;
        }
        $this->billTaxRates[] = $billTaxRate;
        return true;
    }

    public function deleteBillTaxRate(BillTaxRate $billTaxRate): ?bool{
        if (FALSE === $index = array_search( $billTaxRate, $this->getBillTaxRates() ) ){
            return null;
        }
        $billTaxRates = [];
        foreach ($this->getBillTaxRates() as $key=>$value){
            if ($key !== $index){
                $billTaxRates[] = $value;
            }
        }
        $this->billTaxRates = $billTaxRates;
        return true;
    }

    public function getNumber(){
        return $this->getEstablishment() .
            '-' .
            $this->getEmissionPoint() . 
            '-' . 
            $this->getSequential();
    }
    public function generateAccessKey(){
        $accessKey = $this->dateOfIssue->format('dmY');
        $accessKey .= $this->getVoucherType()->getCode();
        $accessKey .= $this->getStore()->getRuc();
        $accessKey .= '2'; //EnvironmentType Tabla 4
        $accessKey .= $this->getEstablishment();
        $accessKey .= $this->getEmissionPoint();
        $accessKey .= $this->getSequential();
        $accessKey .= '00000000'; // This code must be generated by store
        $accessKey .= '1'; // Normal Emission: unique emission type from offline emission
        if(null === $checkDigit = $this->generateCheckDigit($accessKey)){
            throw new \Exception('Cannot generate accessKey: The access key must be 48 digits.');
        }
        $accessKey .= $checkDigit;
        return $accessKey;
    }
    private function generateCheckDigit($accessKey){
        if(strlen($accessKey) !== 48){
            return null;
        }
        $arrayKey = str_split(strrev($accessKey),1);
        $factor = [2,3,4,5,6,7];
        $i = 0;
        $sum = 0;
        foreach ($arrayKey as $digit) {
            $sum += $digit * $factor[$i];
            $i = $i == 5 ? 0 : $i++;
        }
        $mod = $sum % 11;
        $checkDigit = 11 - $mod;
        if($checkDigit == 11){
            $checkDigit = 0;
        }
        if($checkDigit == 10){
            $checkDigit = 1;
        }
        return $checkDigit;
    }
    function toDto():\stdClass
    {
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->accessKey = $this->getAccessKey();
        $dto->establishment = $this->getEstablishment();
        $dto->emissionPoint = $this->getEmissionPoint();
        $dto->sequential = $this->getSequential();
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
        $dto->licensePlate = $this->getLicensePlate();
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
        $dto->billTaxRates = [];
        foreach ($this->billTaxRates as $billTaxRate){
            $dto->billTaxRates[] = $billTaxRate->toDto();
        }
        return $dto;
    }
    
    function toJson(){
        return json_encode($this->toDto());
    }
    
}