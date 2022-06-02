<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\Deductible;
use Infraestructure\Connection\Connection;

/**
 * Description of DeductibleDao
 *
 * @author mauit
 */
class DeductibleDao {

    private static $TABLE = "deductible";
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function toArray(Deductible $deductible) {
        $arr = [];
        $arr['id'] = $deductible->getId();
        $arr['name'] = $deductible->getName();
        $arr['active'] = $deductible->isActive();
        return $arr;
    }

    public function insert(Deductible $deductible) {
        $deductible->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($deductible));
    }

    public function update(Deductible $deductible) {
        return $this->connection->update(self::$TABLE, $this->toArray($deductible));
    }

    public function delete(int $id): int {
        return $this->connection->delete(self::$TABLE, $id);
    }

    public function findOne(array $params):? Deductible {
        $deductible = new Deductible();
        foreach ($params as $property => $value) {
            if (!property_exists($deductible, $property)) {
                return null;
            }
            
        }
        $result = $this->connection->findOne(self::$TABLE, $params);
        
        $deductible->setId($result->id);
        $deductible->setName($result->name);
        $deductible->setActive($result->active);
        return $deductible;
        
    }
    
    public function find():?array{
        if( null === $result = $this->connection->find(self::$TABLE, ['active' => true])){
            return null;
        }
        $deductibles = [];
        foreach ($result as $obj){
            $deductible = new Deductible();
            $deductible->setId($obj->id);
            $deductible->setName($obj->name);
            $deductibles[] = $deductible;
        }
        return $deductibles;
    }

    public function findById(int $id):?Deductible{
        if (null === $result = $this->connection->findById(self::$TABLE, $id)){
            return null;
        }
        $deductible = new Deductible($result->id);
        $deductible->setName($result->name);
        $deductible->setActive($result->active);
        return $deductible;
    }
}
