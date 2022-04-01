<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\ApplicationService;

/**
 * Description of RegisterBillServiceTest
 *
 * @author mauit
 */

use Domain\Bill;
use Domain\Store;
use ApplicationService\RegisterBillService;
use Infraestructure\Connection\ConnectionMySqlTest;

class RegisterBillServiceTest {
    private $connection;
    
    public function __construct() {
        $this->connection = new ConnectionMySqlTest();
        $this->connection->createDataBase();
    }
    
    public function __destruct() {
        $this->connection->dropDataBase();
    }
    
    public function __invoke() {
        echo "Register bill and store";
        $this->createBillAndStoreTest();
        echo "Register only bill. The store exists.";
        $this->createBill();
        
    }
    
    private function createBillAndStoreTest(){
        
        $voucherTypeDao = new \Dao\VoucherTypeDao($this->connection);
        $voucherType = $voucherTypeDao->findOne(['code' => '01']);
                
        $bill = new Bill();
        $bill->setAccessKey("0123456789");
        $bill->setEstablishment("001");
        $bill->setEmissionPoint("001");
        $bill->setSecuential("001");
        $bill->setDateOfIssue("2022-03-23");
        $bill->setEstablishmentAddress("001");
        $bill->setTotalWithoutTax("001");
        $bill->setTotalDiscount("001");
        $bill->setTip("001");
        $bill->setTotal("001");
        $bill->setFilePath("001");
        
        $store = new Store();
        $store->setBusinessName("test business name");
        $store->setTradeName("Test trade name");
        $store->setRuc("0123456789001");
        $store->setParentAddress("Address of business test");
        $bill->setStore($store);
                
        $bill->setVoucherType($voucherType);
        
        $registerBillService = new RegisterBillService($this->connection);
        $result = $registerBillService($bill);
        print "The bill was registred with id $result" . PHP_EOL;
        
    }
    
    private function createBill(){
        $voucherTypeDao = new \Dao\VoucherTypeDao($this->connection);
        $voucherType = $voucherTypeDao->findOne(['code' => '01']);      
        
        $bill = new Bill();
        $bill->setAccessKey("0123456780");
        $bill->setEstablishment("001");
        $bill->setEmissionPoint("001");
        $bill->setSecuential("002");
        $bill->setDateOfIssue("2022-03-23");
        $bill->setEstablishmentAddress("001");
        $bill->setTotalWithoutTax("001");
        $bill->setTotalDiscount("001");
        $bill->setTip("001");
        $bill->setTotal("001");
        $bill->setFilePath("001");
                
        $store = new Store();
        $store->setBusinessName("test business name - this exists");
        $store->setTradeName("Test trade name-exists");
        $store->setRuc("0103456783001");
        $store->setParentAddress("Address of business test");      
        $registerStoreService = new RegisterStoreService($this->connection);
        $store->setId( $registerStoreService($store) );
        $bill->setStore($store);
        
        $bill->setVoucherType($voucherType);
        
        $registerBillService = new RegisterBillService($this->connection);
        $result = $registerBillService($bill);
        print "The bill was registred with id $result" . PHP_EOL;
    }
    
}
