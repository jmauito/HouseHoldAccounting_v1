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
use Domain\BillDetailDeductible;
use Domain\Store;
use Infraestructure\Connection\Connection;
use Dao\StoreDao;
use Domain\Buyer;
use Dao\BuyerDao;
use Dao\BillDetailDeductibleDao;
use Dao\BillExpenseDao;
use Dao\BillTaxRateDao;


class RegisterBillService {

    private $connection;
    private $errors = [];

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function __invoke(Bill $bill):?int {
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
            $this->registerBillExpenses($bill);
            $this->registerBillAdditionalInformation($bill);
            $this->registerBillTaxeRates($bill);

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
            $billDetailId = $billDetailDao->insert($billDetail);
            $this->registerBillDetailDeductible($billDetailId, $billDetail->getBillDetailDeductible());
            $this->registerBillDetailExpense($billDetailId, $billDetail->getBillDetailExpense());
        }
                
    }
    
    private function registerBillDeductibles(Bill $bill){
        foreach ($bill->getBillDeductibles() as $billDeductible){
            $billDeductible->setBillId($bill->getId());
            $billDeductibleDao = new \Dao\BillDeductibleDao($this->connection);
            $billDeductibleDao->insert($billDeductible);
        }
                
    }

    private function registerBillExpenses(Bill $bill){
        foreach ($bill->getBillExpenses() as $billExpense){
            $billExpenseDao = new BillExpenseDao($this->connection, $bill->getId(), $billExpense->getExpense()->getId());
            $billExpenseDao->insert($billExpense);
        }

    }

    private function registerBillDetailDeductible(int $billDetailId, ?BillDetailDeductible $billDetailDeductible){

            if ($billDetailDeductible!==null){
                $billDetailDeductibleDao = new BillDetailDeductibleDao($this->connection, $billDetailId);
                $billDetailDeductibleDao->insert($billDetailDeductible);
            }
    }
    
    private function registerBillDetailExpense(int $billDetailId, ?\Domain\BillDetailExpense $billDetailExpense){

            if ($billDetailExpense!==null){
                $billDetailExpenseDao = new \Dao\BillDetailExpenseDao($this->connection, $billDetailId);
                $billDetailExpenseDao->insert($billDetailExpense);
            }
    }

    private function registerBillAdditionalInformation(Bill $bill){
        foreach ($bill->getBillAdditionalInformation() as $billAdditionalInformation){
            $billAdditionalInformationDao = new BillAdditionalInformationDao($this->connection);
            $billAdditionalInformationDao->setBillId($bill->getId());
            $billAdditionalInformationDao->insert($billAdditionalInformation);
        }

    }

    private function registerBillTaxeRates(Bill $bill){
        foreach ($bill->getBillTaxRates() as $billTaxRate){
            $billTaxRateDao = new BillTaxRateDao($this->connection);
            $billTaxRate->setBillId($bill->getId());
            $billTaxRateDao->insert($billTaxRate);
        }
    }

    public function getErrors():?array{
        return $this->errors;
    }
}
