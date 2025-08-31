<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\BillExpense;
use Infraestructure\Connection\Connection;

/**
 * Description of BillExpenseDao
 *
 * @author mauit
 */
class BillExpenseDao {
    private static $TABLE = "bill_expense";
    private $connection;
    private $billId;
    private $expenseId;
    
    function getBillId(): int {
        return $this->billId;
    }

    function getExpenseId(): int {
        return $this->expenseId;
    }

    public function __construct(Connection $connection, int $billId) {
        $this->connection = $connection;
        $this->billId = $billId;
    }
    
    public function toArray(BillExpense $billExpense):array{
        $arr = [];
        $arr['id'] = $billExpense->getId();
        $arr['value'] = $billExpense->getValue();
        $arr['billId'] = $this->getBillId();
        $arr['expenseId'] = $this->getExpenseId();
        $arr['active'] = $billExpense->isActive();
        return $arr;
    }
    
    public function insert(BillExpense $billExpense){
        $this->expenseId = $billExpense->getExpense()->getId();
        $billExpense->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($billExpense));
    }
    
    public function update(BillExpense $billExpense){
        $this->expenseId = $billExpense->getExpense()->getId();
        return $this->connection->update(self::$TABLE, $this->toArray($billExpense));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne(array $params):? BillExpense{
        
        if (null === $result = $this->connection->findOne(self::$TABLE, $params) ) {
            return null;
        }
        
        $billExpense = new BillExpense($result->id);
        $billExpense->setValue($result->value);
        $billExpense->setActive($result->active);
        
        return $billExpense;
    }
    
    public function findById(int $id):?BillExpense{
        if (null === $result = $this->connection->findById(self::$TABLE, $id)){
            return null;
        }
        $billExpense = new BillExpense($result->id);
        $billExpense->setValue($result->value);
        $billExpense->setActive($result->active);
        return $billExpense;
    }
    
    public function findByBill():? array
    {
        if (null === $result = $this->connection->find(self::$TABLE, [
            'billId' => $this->billId,
            'active' => true
        ])){
            return null;
        }
        $billExpenses = [];
        foreach($result as $value){
            $billExpenses[] = $this->parse($value);
        }
        return $billExpenses;
    }
    
    private function parse(\stdClass $result):BillExpense
    {
        $billExpense = new BillExpense($result->id);
        $billExpense->setValue($result->value);
        $billExpense->setExpenseId($result->expenseId);
        return $billExpense;
    }
    
}
