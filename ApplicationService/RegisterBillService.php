<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationService;

/**
 * Description of RegisterBillService
 *
 * @author mauit
 */

use Dao\BillAdditionalInformationDao;
use Domain\Bill;
use Dao\BillDao;
use Domain\BillAdditionalInformation;
use Domain\Store;
use Infraestructure\Connection\Connection;
use Dao\StoreDao;
use Domain\Buyer;
use Dao\BuyerDao;

class RegisterBillService {

    private $connection;
    private $errors = [];

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function __invoke(Bill $bill) {
        $this->connection->beginTransaction();
        try {
            $storeId = $this->getOrCreateStore($bill->getStore());
            $bill->getStore()->setId($storeId);
            $buyerId = $this->getOrCreateBuyer($bill->getBuyer());
            $bill->getBuyer()->setId($buyerId);
            
            $billDao = new BillDao($this->connection);
            $billId = $billDao->insert($bill);
            $bill->setId($billId);
            $this->registerBillDetail($bill);
            $this->registerBillDeductibles($bill);
            $this->registerBillAdditionalInformation($bill);
            $this->connection->commit();
        } catch (\Exception $exc) {
            $this->connection->rollBack();
            $this->errors[] = $exc->getMessage();
            return null;
        }

        return $billId;
    }

    private function getOrCreateStore(Store $store) {
        $storeDao = new StoreDao($this->connection);
        $storeExist = $storeDao->findOne(['ruc' => $store->getRuc()]);
        if ($storeExist === null) {
            $storeId = $storeDao->insert($store);
        } else {
            $storeId = $storeExist->getId();
        }
        return $storeId;
    }
    
    private function getOrCreateBuyer(Buyer $buyer) {
        $buyerDao = new BuyerDao($this->connection);
        $buyerExist = $buyerDao->findOne(['identification' => $buyer->getIdentification()]);
        if ($buyerExist === null) {
            $buyerId = $buyerDao->insert($buyer);
        } else {
            $buyerId = $buyerExist->getId();
        }
        return $buyerId;
    }
    
    private function registerBillDetail(Bill $bill){
        foreach ($bill->getBillDetails() as $billDetail){
            $billDetailDao = new \Dao\BillDetailDao($this->connection);
            $billDetailDao->setBillId($bill->getId());
            $billDetailDao->insert($billDetail);
        }
                
    }
    
    private function registerBillDeductibles(Bill $bill){
        foreach ($bill->getBillDeductibles() as $billDeductible){
            $billDeductibleDao = new \Dao\BillDeductibleDao($this->connection, $bill->getId(), $billDeductible->getDeductible()->getId());
            $billDeductibleDao->insert($billDeductible);
        }
                
    }

    private function registerBillAdditionalInformation(Bill $bill){
        foreach ($bill->getBillAdditionalInformation() as $billAdditionalInformation){
            $billAdditionalInformationDao = new BillAdditionalInformationDao($this->connection);
            $billAdditionalInformationDao->setBillId($bill->getId());
            $billAdditionalInformationDao->insert($billAdditionalInformation);
        }

    }

    public function getErrors(){
        return $this->errors;
    }
}
