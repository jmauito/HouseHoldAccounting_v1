<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationService;

/**
 * Description of SearchBillService
 *
 * @author mauit
 */

use Infraestructure\Connection\Connection;
use Dao\BillDao;
use Domain\Bill;
use Domain\Store;
use Domain\Buyer;
use Dao\StoreDao;
use Dao\BuyerDao;
use Dao\VoucherTypeDao;
use Dao\DeductibleDao;


class SearchBillService {
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function searchByAccessKey($accessKey):?Bill
    {
        $billDao = new BillDao($this->connection);
        if (null === $bill = $billDao->findOne('accessKey',$accessKey)){
            return null;
        }
        
        $bill->setStore($this->getStore($billDao->getStoreId()));
                
        $bill->setVoucherType( $this->getVoucherType($billDao->getVoucherTypeId()) );
        
        $bill->setBuyer( $this->getBuyer($billDao->getBuyerId()) );
        
        $billDetailDao = new \Dao\BillDetailDao($this->connection);
        $billDetails = $billDetailDao->findByBill($bill->getId());
        foreach ($billDetails as $billDetail){
            $bill->addBillDetail($billDetail);
        }
        
        $billDeductibleDao = new \Dao\BillDeductibleDao($this->connection, $bill->getId());
        $billDeductibles = $billDeductibleDao->findByBill();
        foreach ($billDeductibles as $billDeductible) {
            $deductibleDao = new DeductibleDao($this->connection);
            $billDeductible->setDeductible($deductibleDao->findById($billDeductible->getDeductibleId()));
            $bill->addBillDeductible($billDeductible);
        }
        
        return $bill;
    }
    
    private function getStore(int $storeId):Store
    {
        $storeDao = new StoreDao($this->connection);
        $store = $storeDao->findById($storeId);
        return $store;
    }
    
    private function getBuyer(int $buyerId):Buyer
    {
        $buyerDao = new BuyerDao($this->connection);
        $buyer = $buyerDao->findById($buyerId);
        return $buyer;
    }
    
    private function getVoucherType(int $voucherTypeId):\Domain\VoucherType
    {
        $voucherTypeDao = new VoucherTypeDao($this->connection);
        $voucherType = $voucherTypeDao->findById($voucherTypeId);
        return $voucherType;
    }
    
    
}   

