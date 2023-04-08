<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\Tax;
use Infraestructure\Connection\Connection;

/**
 * Description of TaxDao
 *
 * @author mauit
 */
class TaxDao {

    private static $TABLE = "tax";
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function toArray(Tax $tax) {
        $arr = [];
        $arr['id'] = $tax->getId();
        $arr['name'] = $tax->getName();
        $arr['code'] = $tax->getCode();
        $arr['active'] = $tax->isActive();
        return $arr;
    }

    public function insert(Tax $tax) {
        $tax->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($tax));
    }

    public function update(Tax $tax) {
        return $this->connection->update(self::$TABLE, $this->toArray($tax));
    }

    public function delete(int $id): int {
        return $this->connection->delete(self::$TABLE, $id);
    }

    public function findOne(array $params): ?Tax {
        $tax = new Tax();
        foreach ($params as $property => $value) {
            if (!property_exists($tax, $property)) {
                return null;
            }
            
        }
        if (null ===$result = $this->connection->findOne(self::$TABLE, $params) ){
            return null;
        }
        
        $tax->setId($result->id);
        $tax->setName($result->name);
        $tax->setCode($result->code);
        $tax->setActive($result->active);
        return $tax;
        
    }
    
    public function findById(int $id):?Tax{
        if (null === $result = $this->connection->findById(self::$TABLE, $id) ){
            return null;
        }
        $tax = new Tax($result->id);
        $tax->setCode($result->code);
        $tax->setName($result->name);
        $tax->setActive($result->active);
        return $tax;
    }

}
