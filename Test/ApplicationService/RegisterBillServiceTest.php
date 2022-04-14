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
use Domain\Buyer;
use Dao\BuyerDao;

class RegisterBillServiceTest {
    private $connection;
    
    public function __construct() {
        $this->connection = new ConnectionMySqlTest();
        $this->connection->createDataBase();
    }
    
    public function __destruct() {
        #$this->connection->dropDataBase();
    }
    
    public function __invoke() {
        echo "Register bill and store... \n";
        $this->createBillAndStoreTest();
        echo "Register only bill. The store exists... \n";
        $this->createBill();
        echo "Register bill with deductible... \n";
        $this->createBillWithDeductibles();
        
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

        $buyer = new Buyer();
        $buyer->setIdentificationType("1");
        $buyer->setName("Test buyer");
        $buyer->setIdentification("0000000000");
        $bill->setBuyer($buyer);
        
        $billDetail = new \Domain\BillDetail();
        $billDetail->setDescription("Description of first product");
        $billDetail->setMainCode("001");
        $billDetail->setQuantity(1);
        $billDetail->setTotalPriceWithoutTaxes(100);
        $billDetail->setUnitPrice(1);
        $bill->addBillDetail($billDetail);
        
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
        
        $buyer = new Buyer();
        $buyer->setIdentificationType("1");
        $buyer->setName("Test buyer - exists");
        $buyer->setIdentification("0000000001");
        $bill->setBuyer($buyer);
        $buyerDao = new BuyerDao($this->connection);
        $buyerId = $buyerDao->insert($buyer);
        $buyer->setId($buyerId);
        $bill->setBuyer($buyer);
                
        $registerBillService = new RegisterBillService($this->connection);
        $result = $registerBillService($bill);
        print "The bill was registred with id $result" . PHP_EOL;
    }
    
    private function createBillWithDeductibles(){
        $voucherTypeDao = new \Dao\VoucherTypeDao($this->connection);
        $voucherType = $voucherTypeDao->findOne(['code' => '01']);      
        
        $bill = new Bill();
        $bill->setAccessKey("01234567804");
        $bill->setEstablishment("001");
        $bill->setEmissionPoint("001");
        $bill->setSecuential("003");
        $bill->setDateOfIssue("2022-03-23");
        $bill->setEstablishmentAddress("001");
        $bill->setTotalWithoutTax("001");
        $bill->setTotalDiscount("001");
        $bill->setTip(0);
        $bill->setTotal(100);
        $bill->setFilePath("001");
                
        $store = new Store();
        $store->setBusinessName("test business name - for deductible test");
        $store->setTradeName("Test trade deductible-test");
        $store->setRuc("0103456784001");
        $store->setParentAddress("Address of business test");      
        $registerStoreService = new RegisterStoreService($this->connection);
        $store->setId( $registerStoreService($store) );
        $bill->setStore($store);
        
        $bill->setVoucherType($voucherType);
        
        $buyer = new Buyer();
        $buyer->setIdentificationType("1");
        $buyer->setName("Test buyer - for deductible test");
        $buyer->setIdentification("0000000011");
        $bill->setBuyer($buyer);
        $buyerDao = new BuyerDao($this->connection);
        $buyerId = $buyerDao->insert($buyer);
        $buyer->setId($buyerId);
        $bill->setBuyer($buyer);

               
        $registerBillService = new RegisterBillService($this->connection);
        $bill->setId($registerBillService($bill));
        
        $deductible = new \Domain\Deductible(2);
        $deductible->setName("Hogar");
        $deductibleDao = new \Dao\DeductibleDao($this->connection);
        $deductible->setId($deductibleDao->insert($deductible));
        
        $billDeductible = new \Domain\BillDeductible();
        $billDeductible->setValue(50);
        $billDeductible->setDeductible($deductible);
        $billDeductibleDao = new \Dao\BillDeductibleDao($this->connection,$bill->getId());
        $billDeductibleDao->insert($billDeductible);
        print "Registred bill with id '{$bill->getId()}' and deductible {$deductible->getName()} whith value: {$billDeductible->getValue()} <br>";
    }
    
}
