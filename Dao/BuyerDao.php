<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\Buyer;
use Infraestructure\Connection\Connection;

/**
 * Description of BuyerDao
 *
 * @author mauit
 */
class BuyerDao {
    private static $TABLE = "buyer";
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function toArray(Buyer $buyer){
        $arr = [];
        $arr['id'] = $buyer->getId();
        $arr['identificationType'] = $buyer->getIdentificationType();
        $arr['name'] = $buyer->getName();
        $arr['identification'] = $buyer->getIdentification();
        $arr['active'] = $buyer->isActive();
        return $arr;
    }
    
    public function insert(Buyer $buyer){
        $buyer->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($buyer));
    }
    
    public function update(Buyer $buyer){
        return $this->connection->update(self::$TABLE, $this->toArray($buyer));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne(array $params):? Buyer{
        
        $result = $this->connection->findOne(self::$TABLE, $params);
        
        if ($result ===  null){
            return null;
        }
        
        $buyer = new Buyer($result->id);
        $buyer->setIdentificationType($result->identificationType);
        $buyer->setName($result->name);
        $buyer->setIdentification($result->identification);
        $buyer->setActive($result->active);
        
        return $buyer;
    }
    
    public function findById(int $id){
        if (null === $result = $this->connection->findById(self::$TABLE, $id) ){
            return null;
        }
        $buyer = new Buyer($result->id);
        $buyer->setName($result->name);
        $buyer->setIdentification($result->identification);
        $buyer->setIdentificationType($result->identificationType);
        $buyer->setActive($result->active);
        return $buyer;
    }
}
