<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\BillDetail;
use Infraestructure\Connection\Connection;

/**
 * Description of BillDetailDao
 *
 * @author mauit
 */
class BillDetailDao {
    private static $TABLE = "bill_detail";
    private $connection;
    private $billId;
    
    function getBillId(): int {
        return $this->billId;
    }

    function setBillId(int $billId): void {
        $this->billId = $billId;
    }
        
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function toArray(BillDetail $billDetail){
        $arr = [];
        $arr['id'] = $billDetail->getId();
        $arr['mainCode'] = $billDetail->getMainCode();
        $arr['description'] = $billDetail->getDescription();
        $arr['quantity'] = $billDetail->getQuantity();
        $arr['unitPrice'] = $billDetail->getUnitPrice();
        $arr['discount'] = $billDetail->getDiscount();
        $arr['totalPriceWithoutTaxes'] = $billDetail->getTotalPriceWithoutTaxes();
        $arr['active'] = $billDetail->isActive();
        $arr['billId'] = $this->getBillId();
        return $arr;
    }
    
    public function insert(BillDetail $billDetail){
        $billDetail->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($billDetail));
    }
    
    public function update(BillDetail $billDetail){
        return $this->connection->update(self::$TABLE, $this->toArray($billDetail));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne(array $params):? BillDetail{
        
        $result = $this->connection->findOne(self::$TABLE, $params);
        
        if ($result ===  null){
            return null;
        }
        
        $billDetail = new BillDetail($result->id);
        $billDetail->setMainCode($result->mainCode);
        $billDetail->setDescription($result->description);
        $billDetail->setQuantity($result->quantity);
        $billDetail->setUnitPrice($result->unitPrice);
        $billDetail->setDiscount($result->discount);
        $billDetail->setTotalPriceWithoutTaxes($result->totalPriceWithoutTaxes);
        $billDetail->setActive($result->active);
        
        return $billDetail;
    }
    
    public function findByBill(int $billId):? array
    {
        if (null === $result = $this->connection->find(self::$TABLE, ['billId' => $billId]) ){
            return null;
        }
        $billDetails = [];
        foreach($result as $value){
            $billDetail = new BillDetail($value->id);
            $billDetail->setMainCode($value->mainCode);
            $billDetail->setDescription($value->description);
            $billDetail->setQuantity($value->quantity);
            $billDetail->setUnitPrice($value->unitPrice);
            $billDetail->setDiscount($value->discount);
            $billDetail->setTotalPriceWithoutTaxes($value->totalPriceWithoutTaxes);
            $billDetails[] = $billDetail;
        }
        return $billDetails;
    }
}
