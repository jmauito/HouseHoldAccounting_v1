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
use Domain\Bill;
use Domain\Buyer;
use Domain\Store;
use Domain\BillDetail;

class ReadXmlBillService {

    private $xmlBill;

    public function __construct(string $xmlBill) {
        $this->xmlBill = $this->cleanBillXml($xmlBill);
    }

    public function __invoke(\Infraestructure\Connection\Connection $connection) {
        $xml = new \SimpleXMLElement($this->xmlBill);
        $bill = new Bill();
        $bill->setAccessKey($xml->infoTributaria->claveAcceso);

        $bill->setEstablishment($xml->infoTributaria->estab);
        $bill->setEmissionPoint($xml->infoTributaria->ptoEmi);
        $bill->setSecuential($xml->infoTributaria->secuencial);
        $arrDate = explode('/', $xml->infoFactura->fechaEmision);
        $bill->setDateOfIssue("{$arrDate[2]}-{$arrDate[1]}-{$arrDate[0]}");
        $bill->setEstablishmentAddress($xml->infoFactura->dirEstablecimiento);
        $bill->setTotalWithoutTax($xml->infoFactura->totalSinImpuestos->__toString());
        $bill->setTotalDiscount($xml->infoFactura->totalDescuento->__toString());
        $bill->setTip($xml->infoFactura->propina->__toString());
        $bill->setTotal($xml->infoFactura->importeTotal->__toString());

        $bill->setStore($this->setStore($xml));
        $bill->setBuyer($this->setBuyer($xml));
        $voucherTypeDao = new \Dao\VoucherTypeDao($connection);
        $voucherType = $voucherTypeDao->findOne(['code' => $xml->infoTributaria->codDoc]);
        $bill->setVoucherType($voucherType);
        
        $this->setBillDetails($bill,$xml->detalles);
        
        return $bill;
    }

    private function cleanBillXml($fileContent) {
        $bugStringCdata1 = '<![CDATA[<?xml version="1.0" encoding="UTF-8"?>';
        $bugStringCdata2 = "<![CDATA[<?xml version = '1.0' encoding = 'UTF-8'?>";
        $bugStringSquareBracket = "]]";
        $bugStringLt = '&lt;';
        $bugStringGt = '&gt;';
        $xml = $fileContent;
        $xml = str_replace($bugStringCdata1, "", $xml);
        $xml = str_replace($bugStringSquareBracket, "", $xml);
        $xml = str_replace($bugStringCdata2, "", $xml);
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
}
