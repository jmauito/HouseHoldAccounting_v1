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

use Dao\BillAdditionalInformationDao;
use Dao\BillExpenseDao;
use Dao\ExpenseDao;
use Infraestructure\Connection\Connection;
use Dao\BillDao;
use Dao\BillDeductibleDao;
use Dao\BillDetailDeductibleDao;
use Domain\Bill;
use Domain\Store;
use Domain\Buyer;
use Dao\StoreDao;
use Dao\BuyerDao;
use Dao\VoucherTypeDao;
use Dao\DeductibleDao;
use Dao\BillDetailExpenseDao;
use Dao\BillTaxRateDao;

class BillFinderService {
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    /**
     * Returns the las registered function
     * @param $limit the number of rows retrieved
     * @return array list of bills
     */
    public function findLastRegistered($limit=10){
        $billDao = new BillDao($this->connection);
        return $billDao->findLastRegistered($limit);
    }
    
    public function findById(int $id):?Bill
    {
        $billDao = new BillDao($this->connection);
        if (null === $bill = $billDao->findById($id)){
            return null;
        }
        
        return $this->searchByAccessKey($bill->getAccessKey());
    }

    public function findByYear($year) {
        $billDao = new BillDao($this->connection);
        $bills = $billDao->findByYear($year);
        $billDeductibleDao = new BillDeductibleDao($this->connection);
        $bills = array_map(function($bill) use ($billDeductibleDao) {
            if (NULL !== $billDeductibleList = $billDeductibleDao->findByBill($bill->getId()) ) {
                foreach($billDeductibleList as $billDeductible) {
                    $bill->addBillDeductible($billDeductible);
                }
            }
            return $bill;
        } , $bills);
        return $bills;
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
            $billDetailDeductibleDao = new BillDetailDeductibleDao($this->connection, $billDetail->getId());
            if(null !== $billDetailDeductible = $billDetailDeductibleDao->findByBillDetail() ){
                $billDetail->setBillDetailDeductible($billDetailDeductible);
            }
            $billDetailExpenseDao = new BillDetailExpenseDao($this->connection, $billDetail->getId());
            if(null !== $billDetailExpense = $billDetailExpenseDao->findByBillDetail() ){
                $billDetail->setBillDetailExpense($billDetailExpense);
            }
            $bill->addBillDetail($billDetail);
        }
        $billDeductibleDao = new \Dao\BillDeductibleDao($this->connection);
        if (null !== $billDeductibles = $billDeductibleDao->findByBill($bill->getId()) ){
            foreach ($billDeductibles as $billDeductible) {
                $deductibleDao = new DeductibleDao($this->connection);
                $billDeductible->setDeductible($deductibleDao->findById($billDeductible->getDeductibleId()));
                $bill->addBillDeductible($billDeductible);
            }
        }

        $billAdditionalInformationDao = new BillAdditionalInformationDao($this->connection);
        if(null!==$additionalInformation = $billAdditionalInformationDao->findByBillId($bill->getId())){
            foreach ($additionalInformation as $add){
                $bill->addBillAdditionalInformation($add);
            }
        }
        

        $this->getBillExpenses($bill);

        $this->getTaxRates($bill);

        return $bill;
    }

    public function delete(int $id){
        $billDao = new BillDao($this->connection);
        $billDao->delete($id);
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

    /**
     * @param Bill $bill
     * @return void
     */
    public function getBillExpenses(Bill $bill): void
    {
        $billExpenseDao = new BillExpenseDao($this->connection, $bill->getId());
        $expenseDao = new ExpenseDao($this->connection);
        if (null === $billExpenses = $billExpenseDao->findByBill() ){
            return;
        }
        foreach ($billExpenses as $billExpense) {
            $billExpense->setExpense($expenseDao->findById($billExpense->getExpenseId()));
            $bill->addBillExpense($billExpense);
        }
    }

    public function getTaxRates(Bill $bill): void
    {
        $billTaxRateDao = new BillTaxRateDao($this->connection);
        if (null === $billTaxRates = $billTaxRateDao->findByBillId($bill->getId()) ){
            return;
        }
        
        foreach ($billTaxRates as $billTaxRate) {
            $bill->addBillTaxRate($billTaxRate);
        }
    }


}   

