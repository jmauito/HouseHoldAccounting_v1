<?php

namespace Controllers;

use ApplicationService\ReadXmlBillService;
use ApplicationService\SearchBillService;
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

        $searchBilService = new SearchBillService($connection);
        $billExists = $searchBilService->searchByAccessKey($bill->getAccessKey());

        if ($billExists) {
            $bill = $billExists;
            $title = "Bill already register";
        }

        $deductibleFinderService = new DeductibleFinderService($connection);
        $deductibles = $deductibleFinderService->findAll();

        echo $this->templates->render('bill-edit', [
            'title' => $title,
            'bill' => $bill,
            'deductibles' => $deductibles
        ]);
    }

    public function saveBill() {
        $connection = new ConnectionMySql();
        $bill = $this->jsonToBill(base64_decode($_POST['bill']));
        $deductibleDao = new DeductibleDao($connection);
        foreach ($_POST['bill-deductibles'] as $htmlBillDeductibleCode => $htmlBillDeductibleValue) {
            $deductible = $deductibleDao->findById($htmlBillDeductibleCode);
            $billDeductible = new BillDeductible();
            $billDeductible->setDeductible($deductible);
            $billDeductible->setValue(floatval($htmlBillDeductibleValue));
            $bill->addBillDeductible($billDeductible);
        }
        $registerBillService = new RegisterBillService($connection);
        if (null === $billId = $registerBillService($bill)) {
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

    private function jsonToBill($billJson): \Domain\Bill {
        $bill = new \Domain\Bill();
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
        
        $store = new \Domain\Store();
        $store->setBusinessName($json->store->businessName);
        $store->setTradeName($json->store->tradeName);
        $store->setRuc($json->store->ruc);
        $store->setParentAddress($json->store->parentAddress);
        $bill->setStore($store);

        $voucherType = new \Domain\VoucherType();
        $voucherType->setId($json->voucherType->id);
        $voucherType->setCode($json->voucherType->code);
        $voucherType->setName($json->voucherType->name);
        $bill->setVoucherType($voucherType);

        $buyer = new \Domain\Buyer();
        $buyer->setIdentificationType($json->buyer->identificationType);
        $buyer->setName($json->buyer->name);
        $buyer->setIdentification($json->buyer->ruc);
        $bill->setBuyer($buyer);

        foreach ($json->billDetails as $jsonBillDetail){
            $billDetail = new \Domain\BillDetail();
            $billDetail->setMainCode($jsonBillDetail->mainCode);
            $billDetail->setDescription($jsonBillDetail->description);
            $billDetail->setQuantity($jsonBillDetail->quantity);
            $billDetail->setUnitPrice($jsonBillDetail->unitPrice);
            $billDetail->setDiscount($jsonBillDetail->discount);
            $billDetail->setTotalPriceWithoutTaxes($jsonBillDetail->totalPriceWithoutTaxes);
            $bill->addBillDetail($billDetail);
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

}
