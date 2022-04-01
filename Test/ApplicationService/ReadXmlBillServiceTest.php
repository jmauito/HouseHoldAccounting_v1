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
    public function __invoke() {
        $xmlBill = file_get_contents('testBill.xml');
        $readXmlBillService = new ReadXmlBillService($xmlBill);
        $bill = $readXmlBillService(new \Infraestructure\Connection\ConnectionMySqlTest());
        echo '<pre>';
        print_r($bill);
        echo '</pre>';        
    }
}
