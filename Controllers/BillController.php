<?php

namespace Controllers;

use ApplicationService\ReadXmlBillService;
use ApplicationService\SearchBillService;
use Dao\ExpenseDao;
use Dao\VoucherTypeDao;
use Domain\Bill;
use Domain\BillAdditionalInformation;
use Domain\BillDetail;
use Domain\BillDetailDeductible;
use Domain\BillDetailExpense;
use Domain\BillExpense;
use Domain\Buyer;
use Domain\Store;
use Domain\VoucherType;
use Infraestructure\Connection\ConnectionMySql;
use \DomainService\DeductibleFinderService;
use \Dao\DeductibleDao;
use \Domain\BillDeductible;
use \ApplicationService\RegisterBillService;

class BillController extends Controller {

    public function loadFromXml() {
        echo $this->templates->render('load-xml-file', [
            'title' => 'Load bill from xml file']);
    }

    public function createBillFromXml() {
        $xmlBill = file_get_contents($_FILES['xml-file']['tmp_name']);
        $readXmlService = new ReadXmlBillService($xmlBill);
        $connection = new ConnectionMySql();
        $bill = $readXmlService($connection);

        $title = "New bill";
        $update = false;

        $searchBillService = new SearchBillService($connection);
        $billExists = $searchBillService->searchByAccessKey($bill->getAccessKey());

        if ($billExists) {
            $bill = $billExists;
            $title = "Bill already register";
            $update = true;
        }

        $deductibleFinderService = new DeductibleFinderService($connection);
        $deductibles = $deductibleFinderService->findAll();

        $expenseDao = new ExpenseDao($connection);
        $expenses = $expenseDao->find();

        echo $this->templates->render('bill-edit', [
            'title' => $title,
            'bill' => $bill,
            'deductibles' => $deductibles,
            'expenses' => $expenses,
            'update' => $update
        ]);
    }

    public function createBill(){
        $connection = new ConnectionMySql();
        $bill = new Bill();
        $title = "New bill";

        $deductibleFinderService = new DeductibleFinderService($connection);
        $deductibles = $deductibleFinderService->findAll();

        $expenseDao = new ExpenseDao($connection);
        $expenses = $expenseDao->find();

        echo $this->templates->render('bill-create', [
            'title' => $title,
            'bill' => $bill,
            'deductibles' => $deductibles,
            'expenses' => $expenses,
        ]);

    }
    public function saveBill() {
        $connection = new ConnectionMySql();
        $bill = $this->jsonToBill(base64_decode($_POST['bill']));
        $update = $_POST['update'];

        $this->getBillDeductibleByHtmlPost($connection, $bill);
        $this->registerBillDetailDeductibles($bill);
        $this->registerBillDetailExpenses($bill);

        $registerBillService = new RegisterBillService($connection);
        if (null === $billId = $registerBillService($bill, $update)) {
            echo $this->templates->render('error-view', [
                'title' => 'Error al registrar la factura.',
                'errorMessages' => $registerBillService->getErrors()
            ]);
        } else {
            echo $this->templates->render('success-view', [
                'title' => 'Factura registrada correctamente',
                'message' => 'La factura fue registrada con éxito.'
            ]);
        }
    }

    public function insertBill()
    {
        $connection = new ConnectionMySql();
        $bill = new Bill();
        $bill->setAccessKey('todo');
        $bill->setEstablishment($_POST['establishment']);
        $bill->setSecuential($_POST['secuential']);
        $bill->setEmissionPoint($_POST['emissionPoint']);
        $bill->setDateOfIssue($_POST['dateOfIssue']);
        $bill->setTotalWithoutTax($_POST['totalWithoutTax']);
        $bill->setTotalDiscount($_POST['totalDiscount']);
        $bill->setTip($_POST['tip']);
        $bill->setTotal($_POST['total']);

        $store = new Store();
        $store->setBusinessName($_POST['businessName']);
        $store->setTradeName($_POST['tradeName']);
        $store->setRuc($_POST['ruc']);
        $store->setParentAddress($_POST['parentAddress']);
        $bill->setStore($store);

        $buyer = new Buyer();
        $buyer->setIdentificationType('05');
        $buyer->setName($_POST['name']);
        $buyer->setIdentification($_POST['identification']);
        $bill->setBuyer($buyer);

        $voucherTypeDao = new VoucherTypeDao($connection);
        $voucherType = $voucherTypeDao->findById($_POST['voucherTypeId']);
        $bill->setVoucherType($voucherType);

        $this->getBillDeductibleByHtmlPost($connection, $bill);
        $this->getBillExpensesByHtmlPost($connection, $bill);

        $registerBillService = new RegisterBillService($connection);
        if (null === $billId = $registerBillService($bill, false)) {
            echo $this->templates->render('error-view', [
                'title' => 'Error al registrar la factura.',
                'errorMessages' => $registerBillService->getErrors()
            ]);
        } else {
            echo $this->templates->render('success-view', [
                'title' => 'Factura registrada correctamente',
                'message' => 'La factura fue registrada con éxito.'
            ]);
        }

    }

    private function registerBillDetailDeductibles(Bill $bill){
        for ($i=0; $i < count($bill->getBillDetails()); $i++){
            $billDetail = $bill->getBillDetails()[$i];
            if ($_POST['deductibleId'.$billDetail->getMainCode()]){
                $billDetailDeductible = new BillDetailDeductible();
                $billDetailDeductible->setDeductibleId($_POST['deductibleId'.$billDetail->getMainCode()]);
                $billDetailDeductible->setValue($billDetail->getTotalPriceWithoutTaxes());
                $bill->getBillDetails()[$i]->setBillDetailDeductible($billDetailDeductible);
            }
        }
    }

    private function registerBillDetailExpenses(Bill $bill){
        for ($i=0; $i < count($bill->getBillDetails()); $i++){
            $billDetail = $bill->getBillDetails()[$i];
            if ($_POST['expenseId'.$billDetail->getMainCode()]){
                $billDetailExpense = new BillDetailExpense();
                $billDetailExpense->setExpenseId($_POST['expenseId'.$billDetail->getMainCode()]);
                $billDetailExpense->setValue($billDetail->getTotalPriceWithoutTaxes());
                $bill->getBillDetails()[$i]->setBillDetailExpense($billDetailExpense);
            }
        }
    }

    private function jsonToBill($billJson):? Bill {
        $bill = new Bill();
        if (null === $json = json_decode($billJson)) {
            return null;
        }
        $bill->setAccessKey($json->accessKey);
        $bill->setEstablishment($json->establishment);
        $bill->setEmissionPoint($json->emissionPoint);
        $bill->setSecuential($json->secuential);
        $bill->setDateOfIssue($json->dateOfIssue);
        $bill->setEstablishmentAddress($this->parseEstablishmentAddress($json->establishmentAddress));
        $bill->setTotalWithoutTax($json->totalWithoutTax);
        $bill->setTotalDiscount($json->totalDiscount);
        $bill->setTip($json->tip);
        $bill->setTotal($json->total);
        $bill->setFilePath($json->filePath);
        
        $store = new Store();
        $store->setBusinessName($json->store->businessName);
        $store->setTradeName($json->store->tradeName);
        $store->setRuc($json->store->ruc);
        $store->setParentAddress($json->store->parentAddress);
        $bill->setStore($store);

        $voucherType = new VoucherType();
        $voucherType->setId($json->voucherType->id);
        $voucherType->setCode($json->voucherType->code);
        $voucherType->setName($json->voucherType->name);
        $bill->setVoucherType($voucherType);

        $buyer = new Buyer();
        $buyer->setIdentificationType($json->buyer->identificationType);
        $buyer->setName($json->buyer->name);
        $buyer->setIdentification($json->buyer->ruc);
        $bill->setBuyer($buyer);

        foreach ($json->billDetails as $jsonBillDetail){
            $billDetail = new BillDetail();
            $billDetail->setMainCode($jsonBillDetail->mainCode);
            $billDetail->setDescription($jsonBillDetail->description);
            $billDetail->setQuantity($jsonBillDetail->quantity);
            $billDetail->setUnitPrice($jsonBillDetail->unitPrice);
            $billDetail->setDiscount($jsonBillDetail->discount);
            $billDetail->setTotalPriceWithoutTaxes($jsonBillDetail->totalPriceWithoutTaxes);
            $bill->addBillDetail($billDetail);
        }
        foreach ($json->billAdditionalInformation as $jsonBillAdditionalInformation){
            $billAdditionalInformation = new BillAdditionalInformation();
            $billAdditionalInformation->setName($jsonBillAdditionalInformation->name);
            $billAdditionalInformation->setValue($jsonBillAdditionalInformation->value);
            $bill->addBillAdditionalInformation($billAdditionalInformation);
        }
        return $bill;
    }

    private function parseEstablishmentAddress($establisment) {
        $result = '';
        if (is_string($establisment)) {
            return $establisment;
        }
        if (is_object($establisment)) {
            foreach ($establisment as $value) {
                $result .= " $value";
            }
        }
        return $result;
    }

    /**
     * @param ConnectionMySql $connection
     * @param Bill|null $bill
     * @return void
     */
    public function getBillDeductibleByHtmlPost(ConnectionMySql $connection, ?Bill $bill): void
    {
        $deductibleDao = new DeductibleDao($connection);
        foreach ($_POST['bill-deductibles'] as $htmlBillDeductibleCode => $htmlBillDeductibleValue) {
            $deductible = $deductibleDao->findById($htmlBillDeductibleCode);
            $billDeductible = new BillDeductible();
            $billDeductible->setDeductible($deductible);
            $billDeductible->setValue(floatval($htmlBillDeductibleValue));
            $bill->addBillDeductible($billDeductible);
        }
    }

    /**
     * @param ConnectionMySql $connection
     * @param Bill|null $bill
     * @return void
     */
    public function getBillExpensesByHtmlPost(ConnectionMySql $connection, ?Bill $bill): void
    {
        $expenseDao = new ExpenseDao($connection);
        foreach ($_POST['bill-expenses'] as $htmlBillExpenseCode => $htmlBillExpenseValue) {
            $expense = $expenseDao->findById($htmlBillExpenseCode);
            $billExpense = new BillExpense();
            $billExpense->setExpense($expense);
            $billExpense->setValue(floatval($htmlBillExpenseValue));
            $bill->addBillExpense($billExpense);
        }
    }

}
