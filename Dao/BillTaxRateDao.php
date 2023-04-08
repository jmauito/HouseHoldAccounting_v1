<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\BillTaxRate;
use Infraestructure\Connection\Connection;

/**
 * Description of BillTaxRateDao
 *
 * @author mauit
 */
class BillTaxRateDao {

    private static $TABLE = "bill_tax_rate";
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function toArray(BillTaxRate $billTaxRate) {
        $arr = [];
        $arr['id'] = $billTaxRate->getId();
        $arr['billId'] = $billTaxRate->getBillId();
        $arr['taxRateId'] = $billTaxRate->getTaxRateId();
        $arr['taxBase'] = $billTaxRate->getTaxBase();
        $arr['value'] = $billTaxRate->getValue();
        $arr['active'] = $billTaxRate->isActive();
        return $arr;
    }

    public function insert(BillTaxRate $billTaxRate) {
        $billTaxRate->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($billTaxRate));
    }

    public function update(BillTaxRate $billTaxRate) {
        return $this->connection->update(self::$TABLE, $this->toArray($billTaxRate));
    }

    public function delete(int $id): int {
        return $this->connection->delete(self::$TABLE, $id);
    }

    public function findOne(array $params): ?BillTaxRate {
        $billTaxRate = new BillTaxRate();
        foreach ($params as $property => $value) {
            if (!property_exists($billTaxRate, $property)) {
                return null;
            }
            
        }
        if (null ===$result = $this->connection->findOne(self::$TABLE, $params) ){
            return null;
        }
        $billTaxRate = $this->parse($result);
        return $billTaxRate;
        
    }
    
    public function findById(int $id):?BillTaxRate{
        if (null === $result = $this->connection->findById(self::$TABLE, $id) ){
            return null;
        }
        $billTaxRate = $this->parse($result);
        return $billTaxRate;
    }

    public function findByBillId(int $billId):?Array {
        if (null === $result = $this->connection->find(self::$TABLE, ['billId' => $billId]) ){
            return null;
        }
        $billTaxRate = array_map(function ($res){ return $this->parse($res); }, $result);
        return $billTaxRate;
    }

    private function parse(\stdClass $obj){
        $billTaxRate = new BillTaxRate($obj->id);
        $billTaxRate->setBillId($obj->billId);
        $billTaxRate->setTaxRateId($obj->taxRateId);
        $billTaxRate->setTaxBase($obj->taxBase);
        $billTaxRate->setValue($obj->value);
        $billTaxRate->setActive($obj->active);

        return $billTaxRate;
    }

}
