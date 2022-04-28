<?php

namespace Controllers;

use ApplicationService\ReadXmlBillService;
use ApplicationService\SearchBillService;
use Infraestructure\Connection\ConnectionMySql;


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
            $title = "Bill exists";
        }

        echo $this->templates->render('bill-edit', [
            'title' => "Factura cargada",
            'bill' => $bill
        ]);
    }

}
