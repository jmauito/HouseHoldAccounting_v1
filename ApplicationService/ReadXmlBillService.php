<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationService;

/**
 * Description of ReadBillXmlService
 *
 * @author mauit
 */

use Dao\TaxDao;
use Dao\TaxRateDao;
use Domain\Bill;
use Domain\BillAdditionalInformation;
use Domain\Buyer;
use Domain\Store;
use Domain\BillDetail;
use Domain\BillTaxRate;
use \Infraestructure\Connection\Connection;

class ReadXmlBillService {

    private $xmlBill;
    private $errors = [];

    public function __construct(string $xmlBill) {
        $this->xmlBill = $this->cleanBillXml($xmlBill);
    }

    public function __invoke(Connection $connection) {
        $xml = new \SimpleXMLElement($this->xmlBill);
        $bill = new Bill();
        $bill->setAccessKey($xml->infoTributaria->claveAcceso->__toString());
        $bill->setEstablishment($xml->infoTributaria->estab->__toString());
        $bill->setEmissionPoint($xml->infoTributaria->ptoEmi->__toString());
        $bill->setSequential($xml->infoTributaria->secuencial->__toString());
        $arrDate = explode('/', $xml->infoFactura->fechaEmision);
        $bill->setDateOfIssue( new \DateTime("{$arrDate[2]}-{$arrDate[1]}-{$arrDate[0]}"));
        $bill->setEstablishmentAddress($xml->infoFactura->dirEstablecimiento->__toString());
        $bill->setTotalWithoutTax($xml->infoFactura->totalSinImpuestos->__toString());
        $bill->setTotalDiscount($xml->infoFactura->totalDescuento->__toString());
        if (isset($xml->infoFactura->propina)){
            $bill->setTip($xml->infoFactura->propina->__toString());
        } else {
            $bill->setTip(0.0);
        }
        $bill->setTotal($xml->infoFactura->importeTotal->__toString());
        $bill->setLicensePlate($xml->infoFactura->placa->__toString());
        $bill->setStore($this->setStore($xml));
        $bill->setBuyer($this->setBuyer($xml));
        $voucherTypeDao = new \Dao\VoucherTypeDao($connection);
        if (null === $voucherType = $voucherTypeDao->findOne(['code' => $xml->infoTributaria->codDoc]) ){
            $this->errors[] = "Not found voucherType.code = {$xml->infoTributaria->codDoc}";
            return null;
        }
        $bill->setVoucherType($voucherType);
        
        $this->setTaxes($bill, $xml->infoFactura->totalConImpuestos, $connection);

        $this->setBillDetails($bill,$xml->detalles);

        if (property_exists($xml, 'infoAdicional')){
            $this->setBillAdditionalInformation($bill,$xml->infoAdicional);
        }
        return $bill;
    }

    public function getErrors():?array{
        return $this->errors;
    }

    private function cleanBillXml($fileContent) {
        $bugStringCdata1 = '<![CDATA[<?xml version="1.0" encoding="UTF-8"?>';
        $bugStringCdata2 = "<![CDATA[<?xml version = '1.0' encoding = 'UTF-8'?>";
        $bugStringCdata3 = '<![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $bugStringSquareBracket = "]]";
        $bugStringSquareBracket2 = "]]>";
        $bugStringLt = '&lt;';
        $bugStringGt = '&gt;';
        $xml = $fileContent;
        $xml = str_replace($bugStringCdata1, "", $xml);
        $xml = str_replace($bugStringSquareBracket, "", $xml);
        $xml = str_replace($bugStringCdata2, "", $xml);
        $xml = str_replace($bugStringCdata3, "", $xml);
        $xml = str_replace($bugStringSquareBracket2, "", $xml);
        $xml = str_replace($bugStringLt, "<", $xml);
        $xml = str_replace($bugStringGt, ">", $xml);
        $inicioFactura = strpos($xml, "<factura");
        $finFactura = strpos($xml, "</factura>") + 10 - $inicioFactura;
        $xml = substr($xml, $inicioFactura, $finFactura);
        return $xml;
    }

    private function setStore(\SimpleXMLElement $xml) {
        $store = new Store();
        $store->setBusinessName((string) $xml->infoTributaria->razonSocial);
        $store->setTradeName((string) $xml->infoTributaria->nombreComercial);
        $store->setRuc((string) $xml->infoTributaria->ruc);
        $store->setParentAddress((string) $xml->infoTributaria->dirMatriz);
        return $store;
    }

    private function setBuyer(\SimpleXMLElement $xml) {
        $buyer = new Buyer();
        $buyer->setIdentificationType((string) $xml->infoFactura->tipoIdentificacionComprador);
        $buyer->setName((string) $xml->infoFactura->razonSocialComprador);
        $buyer->setIdentification((string) $xml->infoFactura->identificacionComprador);
        return $buyer;
    }

    private function setBillDetails(Bill $bill, \SimpleXMLElement $xml){
        
        foreach ($xml->detalle as $detail) {
            $billDetail = new BillDetail();
            $billDetail->setMainCode($detail->codigoPrincipal);
            $billDetail->setDescription($detail->descripcion);
            $billDetail->setQuantity((float)$detail->cantidad);
            $billDetail->setUnitPrice((float)$detail->precioUnitario);
            $billDetail->setDiscount((float)$detail->descuento);
            $billDetail->setTotalPriceWithoutTaxes((float)$detail->precioTotalSinImpuesto);
            
            $bill->addBillDetail($billDetail);
        }
    }

    private function setBillAdditionalInformation(Bill $bill, \SimpleXMLElement $xml){

        foreach ($xml->campoAdicional as $additionalField) {
            $billAdditionalInformation = new BillAdditionalInformation();
            $billAdditionalInformation->setName($additionalField['nombre']);
            $billAdditionalInformation->setValue($additionalField[0]);

            $bill->addBillAdditionalInformation($billAdditionalInformation);
        }
    }

    private function setTaxes(Bill $bill, \SimpleXMLElement $xml, Connection $connection){
        
        $taxDao = new TaxDao($connection);
        $taxRateDao = new TaxRateDao($connection);

        foreach($xml->totalImpuesto as $impuesto){
            if ($impuesto->baseImponible == 0) continue;
            $tax = $taxDao->findOne(['code'=> $impuesto->codigo]);
            $taxRate = $taxRateDao->findOne(['taxId' => $tax->getId(), 'code' => $impuesto->codigoPorcentaje]);
            $billTaxRate = new BillTaxRate();
            $billTaxRate->setBillId($bill->getId());
            $billTaxRate->setTaxRateId($taxRate->getId());
            $billTaxRate->setTaxBase((float) $impuesto->baseImponible);
            $billTaxRate->setValue((float) $impuesto->valor);
            
            $bill->addBillTaxRate($billTaxRate);

        }
        
    }
}
