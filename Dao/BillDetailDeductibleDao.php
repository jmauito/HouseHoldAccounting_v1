<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\BillDetailDeductible;
use Infraestructure\Connection\Connection;

/**
 * Description of BillDetailDeductible
 *
 * @author mauit
 */
class BillDetailDeductibleDao {
    private static $TABLE = "bill_detail_deductible";
    private $connection;
    private $billDetailId;
    private $deductibleId;

    function getBillDetailId(): int {
        return $this->billDetailId;
    }

    function getDeductibleId():int{
        return $this->deductibleId;
    }

    public function __construct(Connection $connection, int $billDetailId) {
        $this->connection = $connection;
        $this->billDetailId = $billDetailId;
    }
    
    public function toArray(BillDetailDeductible $billDetailDeductible):array{
        $arr = [];
        $arr['id'] = $billDetailDeductible->getId();
        $arr['value'] = $billDetailDeductible->getValue();
        $arr['billDetailId'] = $this->getBillDetailId();
        $arr['deductibleId'] = $this->getDeductibleId();
        $arr['active'] = $billDetailDeductible->isActive();
        return $arr;
    }
    
    public function insert(BillDetailDeductible $billDetailDeductible){
        $this->deductibleId = $billDetailDeductible->getDeductibleId();
        $billDetailDeductible->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($billDetailDeductible));
    }
    
    public function update(BillDetailDeductible $billDetailDeductible){
        $this->deductibleId = $billDetailDeductible->getDeductible()->getId();
        return $this->connection->update(self::$TABLE, $this->toArray($billDetailDeductible));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne(array $params):? BillDetailDeductible{
        
        if (null === $result = $this->connection->findOne(self::$TABLE, $params) ) {
            return null;
        }
        
        $billDetailDeductible = new BillDetailDeductible($result->id);
        $billDetailDeductible->setValue($result->value);
        $billDetailDeductible->setActive($result->active);
        
        return $billDetailDeductible;
    }
    
    public function findById(int $id):?BillDetailDeductible{
        if (null === $result = $this->connection->findById(self::$TABLE, $id)){
            return null;
        }
        $billDetailDeductible = new BillDetailDeductible($result->id);
        $billDetailDeductible->setValue($result->value);
        $billDetailDeductible->setActive($result->active);
        return $billDetailDeductible;
    }
    
    public function findByBillDetail():?BillDetailDeductible
    {
        if (null === $result = $this->connection->findOne(self::$TABLE, [
            'billDetailId' => $this->billDetailId,
            'active' => true
        ])){
            return null;
        }
        return $this->parse($result);
    }
    
    private function parse(\stdClass $result):BillDetailDeductible
    {
        $billDetailDeductible = new BillDetailDeductible($result->id);
        $billDetailDeductible->setValue($result->value);
        $billDetailDeductible->setDeductibleId($result->deductibleId);
        $billDetailDeductible->setActive($result->active);
        return $billDetailDeductible;
    }
    
}
