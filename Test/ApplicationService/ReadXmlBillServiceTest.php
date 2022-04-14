<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\ApplicationService;

/**
 * Description of ReadXmlBillServiceTest
 *
 * @author mauit
 */

use ApplicationService\ReadXmlBillService;

class ReadXmlBillServiceTest {
    private $connection;
    
    public function __construct(\Infraestructure\Connection\Connection $connection) {
        $this->connection = $connection;
    }
    
    public function __destruct() {
        #$this->connection->dropDatabase();
    }
    
    public function __invoke() {
        $bill = $this->convertXmlToBill();
        echo '<pre>';
        print_r($bill);
        echo '</pre>';
        $id = $this->registerBill($bill);
        print("Register bill whit id: '$id'");
    }
    
    private function convertXmlToBill(){
        $xmlBill = file_get_contents('testBill.xml');
        $readXmlBillService = new ReadXmlBillService($xmlBill);
        $bill = $readXmlBillService($this->connection);
        return $bill;
    }
    
    private function registerBill(\Domain\Bill $bill){
        $registerBillService = new \ApplicationService\RegisterBillService($this->connection);
        $id = $registerBillService($bill);
        return $id;
    }
}
