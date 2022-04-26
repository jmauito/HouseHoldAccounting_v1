<?php

include '../vendor/autoload.php';

use ApplicationService\ReadXmlBillService;
use ApplicationService\SearchBillService;
use Infraestructure\Connection\ConnectionMySql;
use League\Plates\Engine;

if (key_exists('xml-file', $_FILES)) {
    viewXmlBill();
} else {
    loadXmlFile();
}

function viewXmlBill() {
    $xmlBill = file_get_contents($_FILES['xml-file']['tmp_name']);
    $readXmlService = new ReadXmlBillService($xmlBill);
    $connection = new ConnectionMySql();
    $bill = $readXmlService($connection);
    
    $title = "New bill";
    
    $searchBilService = new SearchBillService($connection);
    $billExists = $searchBilService->searchByAccessKey($bill->getAccessKey());
    
    if ($billExists){
        $bill = $billExists;
        $title = "Bill exists";
    }
    
    $templates = new Engine('../Views');
    echo $templates->render('bill-edit', [
        'title' => "Factura cargada",
        'bill' => $bill
    ]);
}

function loadXmlFile() {
    $templates = new Engine('../Views');

    echo $templates->render('load-xml-file', [
        'title' => "Load xml file"
    ]);
}
