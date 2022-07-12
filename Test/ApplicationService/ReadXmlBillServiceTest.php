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

use Dao\VoucherTypeDao;
use Infraestructure\Connection\ConnectionMySqlTest;
use ApplicationService\ReadXmlBillService;
use PHPUnit\Framework\TestCase;
use Domain\Bill;

class ReadXmlBillServiceTest extends TestCase{

    public function testCanParseXmlToBill():void
    {
        $connection = new ConnectionMySqlTest();
        $voucherTypeDao = new VoucherTypeDao($connection);
        $voucherTypeMother = new \Test\Domain\VoucherTypeMother();
        $voucherTypeDao->insert($voucherTypeMother->build());
        $xmlBill = file_get_contents('Test/testBill.xml');
        $readXmlBillService = new ReadXmlBillService($xmlBill);
        $bill = $readXmlBillService($connection);
        if ($bill === null){
            echo(implode($readXmlBillService->getErrors() ));
        }

        $billMother = new \Test\Domain\BillMother();
        $billTest = $billMother->buildTestBill();

        $this->assertInstanceOf(Bill::class, $bill );
        $this->assertEquals($billTest->toJson(), $bill->toJson());
    }
    

}
