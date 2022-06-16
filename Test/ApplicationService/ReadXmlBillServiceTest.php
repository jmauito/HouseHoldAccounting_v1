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

use Infraestructure\Connection\ConnectionMySqlTest;
use ApplicationService\ReadXmlBillService;
use PHPUnit\Framework\TestCase;
use Domain\Bill;

class ReadXmlBillServiceTest{
    // private $connection;
    
    /*public function __construct(\Infraestructure\Connection\Connection $connection) {
        parent::__construct();
        $this->connection = $connection;
    }*/
    
    public function testCanParseXmlToBill():void
    {
        $connection = new ConnectionMySqlTest();
        $xmlBill = file_get_contents('Test/testBill.xml');
        $readXmlBillService = new ReadXmlBillService($xmlBill);
        $bill = $readXmlBillService($connection);
        $this->assertInstanceOf(Bill::class, $bill );
    }
    
    public function testCanRegisterBill():void{
        $connection = new ConnectionMySqlTest();
        $bill = BillMother::random();
        print_r($bill);
        $registerBillService = new \ApplicationService\RegisterBillService($connection);
        $id = $registerBillService($bill);
        $this->assertEquals($bill->getId(), $id);
    }
    
    /*public function __destruct() {
        $this->connection->dropDatabase();
    }*/
    
    /*public function __invoke() {
        $bill = $this->convertXmlToBill();
        echo '<pre>';
        print_r($bill);
        echo '</pre>';
        $id = $this->registerBill($bill);
        print("Register bill whit id: '$id'");
    }
    
    
    
    */
}
