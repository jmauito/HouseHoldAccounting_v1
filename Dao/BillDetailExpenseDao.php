<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\BillDetailExpense;
use Infraestructure\Connection\Connection;

/**
 * Description of BillDetailExpense
 *
 * @author mauit
 */
class BillDetailExpenseDao {
    private static $TABLE = "bill_detail_expense";
    private $connection;
    private $billDetailId;
    private $expenseId;

    function getBillDetailId(): int {
        return $this->billDetailId;
    }

    function getExpenseId():int{
        return $this->expenseId;
    }

    public function __construct(Connection $connection, int $billDetailId) {
        $this->connection = $connection;
        $this->billDetailId = $billDetailId;
    }
    
    public function toArray(BillDetailExpense $billDetailExpense):array{
        $arr = [];
        $arr['id'] = $billDetailExpense->getId();
        $arr['value'] = $billDetailExpense->getValue();
        $arr['billDetailId'] = $this->getBillDetailId();
        $arr['expenseId'] = $this->getExpenseId();
        $arr['active'] = $billDetailExpense->isActive();
        return $arr;
    }
    
    public function insert(BillDetailExpense $billDetailExpense):int{
        $this->expenseId = $billDetailExpense->getExpenseId();
        $billDetailExpense->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($billDetailExpense));
    }
    
    public function update(BillDetailExpense $billDetailExpense):int{
        $this->expenseId = $billDetailExpense->getExpense()->getId();
        return $this->connection->update(self::$TABLE, $this->toArray($billDetailExpense));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne(array $params):? BillDetailExpense{
        
        if (null === $result = $this->connection->findOne(self::$TABLE, $params) ) {
            return null;
        }
        
        $billDetailExpense = new BillDetailExpense($result->id);
        $billDetailExpense->setValue($result->value);
        $billDetailExpense->setActive($result->active);
        
        return $billDetailExpense;
    }
    
    public function findById(int $id):?BillDetailExpense{
        if (null === $result = $this->connection->findById(self::$TABLE, $id)){
            return null;
        }
        $billDetailExpense = new BillDetailExpense($result->id);
        $billDetailExpense->setValue($result->value);
        $billDetailExpense->setActive($result->active);
        return $billDetailExpense;
    }
    
    public function findByBillDetail():?BillDetailExpense
    {
        if (null === $result = $this->connection->findOne(self::$TABLE, [
            'billDetailId' => $this->billDetailId,
            'active' => true
        ])){
            return null;
        }
        return $this->parse($result);
    }
    
    private function parse(\stdClass $result):BillDetailExpense
    {
        $billDetailExpense = new BillDetailExpense($result->id);
        $billDetailExpense->setValue($result->value);
        $billDetailExpense->setExpenseId($result->expenseId);
        $billDetailExpense->setActive($result->active);
        return $billDetailExpense;
    }
    
}
