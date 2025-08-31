<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\TaxRate;
use Infraestructure\Connection\Connection;
use Infraestructure\Exception\EntityNotFoundException;

/**
 * Description of TaxRateDao
 *
 * @author mauit
 */
class TaxRateDao {

    private static $TABLE = "tax_rate";
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function toArray(TaxRate $taxRate) {
        $arr = [];
        $arr['id'] = $taxRate->getId();
        $arr['taxId'] = $taxRate->getTaxId();
        $arr['name'] = $taxRate->getName();
        $arr['code'] = $taxRate->getCode();
        $arr['active'] = $taxRate->isActive();
        return $arr;
    }

    public function insert(TaxRate $taxRate) {
        $taxRate->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($taxRate));
    }

    public function update(TaxRate $taxRate) {
        return $this->connection->update(self::$TABLE, $this->toArray($taxRate));
    }

    public function delete(int $id): int {
        return $this->connection->delete(self::$TABLE, $id);
    }

    public function findOne(array $params): ?TaxRate {
        $taxRate = new TaxRate();
        foreach ($params as $property => $value) {
            if (!property_exists($taxRate, $property)) {
                return null;
            }
            
        }
        if (null === $result = $this->connection->findOne(self::$TABLE, $params) ){
            throw new EntityNotFoundException(TaxRate::class, $params);
        }
        
        $taxRate->setId($result->id);
        $taxRate->setTaxId($result->taxId);
        $taxRate->setName($result->name);
        $taxRate->setCode($result->code);
        $taxRate->setActive($result->active);
        return $taxRate;
        
    }
    
    public function findById(int $id):?TaxRate{
        if (null === $result = $this->connection->findById(self::$TABLE, $id) ){
            return null;
        }
        $taxRate = new TaxRate($result->id);
        $taxRate->setTaxId($result->taxId);
        $taxRate->setCode($result->code);
        $taxRate->setName($result->name);
        $taxRate->setActive($result->active);
        return $taxRate;
    }

}
