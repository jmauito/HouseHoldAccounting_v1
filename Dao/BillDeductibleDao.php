<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\BillDeductible;
use Infraestructure\Connection\Connection;

/**
 * Description of BillDeductibleDao
 *
 * @author mauit
 */
class BillDeductibleDao {
    private static $TABLE = "bill_deductible";
    private $connection;
    private $deductibleId;

    function getDeductibleId(): int {
        return $this->deductibleId;
    }

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function toArray(BillDeductible $billDeductible){
        $arr = [];
        $arr['id'] = $billDeductible->getId();
        $arr['value'] = $billDeductible->getValue();
        $arr['billId'] = $billDeductible->getBillId();
        $arr['deductibleId'] = $this->getDeductibleId();
        $arr['active'] = $billDeductible->isActive();
        return $arr;
    }
    
    public function insert(BillDeductible $billDeductible){
        $this->deductibleId = $billDeductible->getDeductible()->getId();
        $billDeductible->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($billDeductible));
    }
    
    public function update(BillDeductible $billDeductible){
        $this->deductibleId = $billDeductible->getDeductible()->getId();
        return $this->connection->update(self::$TABLE, $this->toArray($billDeductible));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne(array $params):? BillDeductible{
        
        if (null === $result = $this->connection->findOne(self::$TABLE, $params) ) {
            return null;
        }
        
        $billDeductible = new BillDeductible($result->id);
        $billDeductible->setBillId($result->billId);
        $billDeductible->setValue($result->value);
        $billDeductible->setActive($result->active);
        
        return $billDeductible;
    }
    
    public function findById(int $id):?BillDeductible{
        if (null === $result = $this->connection->findById(self::$TABLE, $id)){
            return null;
        }
        $billDeductible = new BillDeductible($result->id);
        $billDeductible->setBillId($result->billId);
        $billDeductible->setValue($result->value);
        $billDeductible->setActive($result->active);
        return $billDeductible;
    }
    
    public function findByBill(int $billId):? array
    {
        if (null === $result = $this->connection->find(self::$TABLE, [
            'billId' => $billId,
            'active' => true
        ])){
            return null;
        }
        $billDeductibles = [];
        foreach($result as $value){
            $billDeductibles[] = $this->parse($value);
        }
        return $billDeductibles;
    }

    public function findByDeductibleId(int $deductibleId): array
    {
        if (null === $result = $this->connection->find(self::$TABLE, [
            'deductibleId' => $deductibleId,
            'active' => true
        ])){
            return null;
        }
        $billDeductibles = [];
        foreach($result as $value){
            $billDeductibles[] = $this->parse($value);
        }
        return $billDeductibles;
    }
    
    private function parse(\stdClass $result):BillDeductible
    {
        $billDeductible = new BillDeductible($result->id);
        $billDeductible->setBillId($result->billId);
        $billDeductible->setValue($result->value);
        $billDeductible->setDeductibleId($result->deductibleId);
        return $billDeductible;
    }
    
}
