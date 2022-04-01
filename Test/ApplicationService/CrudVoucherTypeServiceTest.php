<?php
namespace Test\ApplicationService;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createVoucherTypeServiceTest
 *
 * @author mauit
 */

use Dao\VoucherTypeDao;
use Domain\VoucherType;
use Infraestructure\Connection\ConnectionMySql;

class CrudVoucherTypeServiceTest {
    private $connection;
    public function __construct() {
        $this->connection = new ConnectionMySql;
    }
    
    public function __invoke() {
        $this->createVoucherTest();
        $this->updateVoucherTest();
        $this->deleteVoucherTest();
    }
    
    private function createVoucherTest(){
        $voucherType = new VoucherType(1);
        $voucherType->setName("test voucher");
        $voucherType->setCode("01");
        $voucherTypeDao = new VoucherTypeDao($this->connection);
        $id = $voucherTypeDao->insert($voucherType);
        echo "Saved whith id: $id";
    }
    private function updateVoucherTest(){
        $voucherType = new VoucherType(1);
        $voucherType->setName("test voucher-edited");
        $voucherType->setCode("01");
        $voucherType->setActive(false);
        $voucherTypeDao = new VoucherTypeDao($this->connection);
        $rowsUpdated = $voucherTypeDao->update($voucherType);
        echo "Updated $rowsUpdated  records";
    }
    private function deleteVoucherTest(){
        $voucherTypeDao = new VoucherTypeDao($this->connection);
        $rowsDeleted = $voucherTypeDao->delete(1);
        echo "Deleted $rowsDeleted  records";
    }
}
