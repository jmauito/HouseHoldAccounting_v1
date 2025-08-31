<?php

namespace Controllers;

use ApplicationService\ReadXmlBillService;
use ApplicationService\BillFinderService;
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
use \ApplicationService\UpdateBillService;
use Domain\BillTaxRate;
use Infraestructure\Exception\EntityNotFoundException;

use function PHPUnit\Framework\isNull;

class BillController extends Controller
{

    public function loadFromXml()
    {
        echo $this->templates->render('load-xml-file', [
            'title' => 'Load bill from xml file'
        ]);
    }

    public function createBillFromXml()
    {
        $xmlBill = file_get_contents($_FILES['xml-file']['tmp_name']);
        $readXmlService = new ReadXmlBillService($xmlBill);
        $connection = new ConnectionMySql();
        try{
            $bill = $readXmlService($connection);
        } catch (EntityNotFoundException $e) {
            print $e->getErrorMessage();
            die();
        }
        if(null === $bill){
            print_r($readXmlService->getErrors());
            die();
        }

        $title = "New bill";
        $update = false;

        $searchBillService = new BillFinderService($connection);
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

        echo $this->templates->render('bill-form', [
            'title' => $title,
            'bill' => $bill,
            'deductibles' => $deductibles,
            'expenses' => $expenses,
            'update' => $update
        ]);
    }

    public function findById(int $id)
    {
        $connection = new ConnectionMySql();
        $billFinderService = new BillFinderService($connection);
        if (null === $billExists = $billFinderService->findById($id)) {
            echo $this->templates->render('404', []);
            return;
        }

        $deductibleFinderService = new DeductibleFinderService($connection);
        $deductibles = $deductibleFinderService->findAll();

        $expenseDao = new ExpenseDao($connection);
        $expenses = $expenseDao->find();

        echo $this->templates->render('bill-form', [
            'title' => 'Bill update',
            'bill' => $billExists,
            'deductibles' => $deductibles,
            'expenses' => $expenses,
            'update' => true
        ]);
    }

    public function createBill()
    {
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

    public function registerBill()
    {
        $connection = new ConnectionMySql();
        $bill = $this->jsonToBill(base64_decode($_POST['bill']));
        $update = $_POST['update'];

        $this->getBillDeductiblesByHtmlPost($connection, $bill);
        $this->getBillExpensesByHtmlPost($connection, $bill);
        $this->addBillDetailDeductibles($bill);
        $this->addBillDetailExpenses($bill);

        if ($update == false) {
            $registerBillService = new RegisterBillService($connection);
            if (null === $billId = $registerBillService($bill, $update)) {
                echo $this->templates->render('error-view', [
                    'title' => 'Error al registrar la factura.',
                    'errorMessages' => $registerBillService->getErrors()
                ]);
            } else {
                echo $this->templates->render('success-view', [
                    'title' => 'Factura registrada correctamente',
                    'message' => 'La factura fue registrada con éxito.',
                    'billId' => $billId
                ]);
            }
        } else {
            $updateBillService = new UpdateBillService($connection);
            if (null === $updated = $updateBillService($bill)) {
                echo $this->templates->render('error-view', [
                    'title' => 'Error al registrar la factura.',
                    'errorMessages' => $updateBillService->getErrors()
                ]);
            } else {
                echo $this->templates->render('success-view', [
                    'title' => 'Factura actualizada correctamente',
                    'message' => 'La factura fue actualizada con éxito.',
                    'billId' => $bill->getId()
                ]);
            }
        }
    }

    public function insertBill()
    {
        try {
            $connection = new ConnectionMySql();
            $bill = new Bill();
            $bill->setEstablishment($_POST['establishment']);
            $bill->setSequential($_POST['sequential']);
            $bill->setEmissionPoint($_POST['emissionPoint']);
            $bill->setDateOfIssue(new \DateTime($_POST['dateOfIssue']));
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

            $this->getBillDeductiblesByHtmlPost($connection, $bill);
            $this->getBillExpensesByHtmlPost($connection, $bill);

            if (key_exists('mainCode', $_POST)) {
                $this->getBillDetailsByHtmlPost($connection, $bill);
            }

            $bill->setAccessKey($bill->generateAccessKey());

            $this->addBillDetailDeductibles($bill);
        } catch (\Exception $e) {
            echo $this->templates->render('error-view', [
                'title' => 'Error al registrar la factura.',
                'errorMessages' => [$e->getMessage()]
            ]);
            die();
        }
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

    public function confirmDeleteById(int $id)
    {
        $connection = new ConnectionMySql();
        $billFinderService = new BillFinderService($connection);
        if (null === $billExists = $billFinderService->findById($id)) {
            echo $this->templates->render('404', []);
            return;
        }

        $deductibleFinderService = new DeductibleFinderService($connection);
        $deductibles = $deductibleFinderService->findAll();

        $expenseDao = new ExpenseDao($connection);
        $expenses = $expenseDao->find();

        echo $this->templates->render('bill-delete', [
            'title' => 'Confirm delete',
            'bill' => $billExists,
            'deductibles' => $deductibles,
            'expenses' => $expenses
        ]);
    }

    public function deleteById()
    {
        $connection = new ConnectionMySql();
        $billFinderService = new BillFinderService($connection);
        $id = $_POST['billId'];
        $billFinderService->delete($id);

        echo $this->templates->render('success-view', [
            'title' => 'Factura eliminada correctamente',
            'message' => 'La factura fue eliminada con éxito.'
        ]);
    }



    private function addBillDetailDeductibles(Bill $bill)
    {
        for ($i = 0; $i < count($bill->getBillDetails()); $i++) {
            $billDetail = $bill->getBillDetails()[$i];
            if ($_POST['deductibleId' . $billDetail->getMainCode()]) {
                $billDetailDeductible = new BillDetailDeductible();
                $billDetailDeductible->setDeductibleId($_POST['deductibleId' . $billDetail->getMainCode()]);
                $billDetailDeductible->setValue($billDetail->getTotalPriceWithoutTaxes());
                $bill->getBillDetails()[$i]->setBillDetailDeductible($billDetailDeductible);
            }
        }
    }

    private function addBillDetailExpenses(Bill $bill)
    {
        for ($i = 0; $i < count($bill->getBillDetails()); $i++) {
            $billDetail = $bill->getBillDetails()[$i];
            if ($_POST['expenseId' . $billDetail->getMainCode()]) {
                $billDetailExpense = new BillDetailExpense();
                $billDetailExpense->setExpenseId($_POST['expenseId' . $billDetail->getMainCode()]);
                $billDetailExpense->setValue($billDetail->getTotalPriceWithoutTaxes());
                $bill->getBillDetails()[$i]->setBillDetailExpense($billDetailExpense);
            }
        }
    }

    private function jsonToBill($billJson): ?Bill
    {
        $bill = new Bill();
        if (null === $json = json_decode($billJson)) {
            return null;
        }
        $bill->setId($json->id);
        $bill->setAccessKey($json->accessKey);
        $bill->setEstablishment($json->establishment);
        $bill->setEmissionPoint($json->emissionPoint);
        $bill->setSequential($json->sequential);
        $bill->setDateOfIssue(new \DateTime($json->dateOfIssue->date));
        $bill->setEstablishmentAddress($this->parseEstablishmentAddress($json->establishmentAddress));
        $bill->setTotalWithoutTax($json->totalWithoutTax);
        $bill->setTotalDiscount($json->totalDiscount);
        $bill->setTip($json->tip);
        $bill->setTotal($json->total);
        $bill->setFilePath($json->filePath);
        $bill->setLicensePlate($json->licensePlate);

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

        foreach ($json->billDetails as $jsonBillDetail) {
            $billDetail = new BillDetail($jsonBillDetail->id);
            $billDetail->setMainCode($jsonBillDetail->mainCode);
            $billDetail->setDescription($jsonBillDetail->description);
            $billDetail->setQuantity($jsonBillDetail->quantity);
            $billDetail->setUnitPrice($jsonBillDetail->unitPrice);
            $billDetail->setDiscount($jsonBillDetail->discount);
            $billDetail->setTotalPriceWithoutTaxes($jsonBillDetail->totalPriceWithoutTaxes);
            $bill->addBillDetail($billDetail);
        }
        foreach ($json->billAdditionalInformation as $jsonBillAdditionalInformation) {
            $billAdditionalInformation = new BillAdditionalInformation();
            $billAdditionalInformation->setName($jsonBillAdditionalInformation->name);
            $billAdditionalInformation->setValue($jsonBillAdditionalInformation->value);
            $bill->addBillAdditionalInformation($billAdditionalInformation);
        }
        foreach ($json->billTaxRates as $jsonBillTaxRate) {
            $billTaxRate = new BillTaxRate();
            $billTaxRate->setId($jsonBillTaxRate->id);
            $billTaxRate->setBillId($jsonBillTaxRate->billId);
            $billTaxRate->setTaxRateId($jsonBillTaxRate->taxRateId);
            $billTaxRate->setTaxBase($jsonBillTaxRate->taxBase);
            $billTaxRate->setValue($jsonBillTaxRate->value);
            
            $bill->addBillTaxRate($billTaxRate);
        }
        return $bill;
    }

    private function parseEstablishmentAddress($establisment)
    {
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
    public function getBillDeductiblesByHtmlPost(ConnectionMySql $connection, ?Bill $bill): void
    {
        $deductibleDao = new DeductibleDao($connection);
        foreach ($_POST['bill-deductibles'] as $htmlBillDeductibleCode => $htmlBillDeductibleValue) {
            if ($htmlBillDeductibleValue > 0) {
                $deductible = $deductibleDao->findById($htmlBillDeductibleCode);
                $billDeductible = new BillDeductible();
                $billDeductible->setBillId($bill->getId());
                $billDeductible->setDeductible($deductible);
                $billDeductible->setValue(floatval($htmlBillDeductibleValue));
                $bill->addBillDeductible($billDeductible);
            }
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

    private function getBillDetailsByHtmlPost(ConnectionMySql $connection, ?Bill $bill): void
    {
        foreach ($_POST['mainCode'] as $mainCode) {
            $billDetail = new BillDetail();
            $billDetail->setMainCode($mainCode);
            $billDetail->setDescription($_POST['description'][$mainCode]);
            $billDetail->setQuantity($_POST['quantity'][$mainCode]);
            $billDetail->setUnitPrice($_POST['unitPrice'][$mainCode]);
            $billDetail->setDiscount($_POST['discount'][$mainCode]);
            $billDetail->setTotalPriceWithoutTaxes($_POST['totalPriceWithoutTaxes'][$mainCode]);
            $bill->addBillDetail($billDetail);
        }
    }
}
