<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\Store;
use Infraestructure\Connection\Connection;

/**
 * Description of StoreDao
 *
 * @author mauit
 */
class StoreDao {
    private static $TABLE = "store";
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function toArray(Store $store){
        $arr = [];
        $arr['id'] = $store->getId();
        $arr['businessName'] = $store->getBusinessName();
        $arr['tradeName'] = $store->getTradeName();
        $arr['ruc'] = $store->getRuc();
        $arr['parentAddress'] = $store->getParentAddress();
        $arr['active'] = $store->isActive();
        return $arr;
    }
    
    public function insert(Store $store){
        $store->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($store));
    }
    
    public function update(Store $store){
        return $this->connection->update(self::$TABLE, $this->toArray($store));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne(array $params):? Store{
        
        $result = $this->connection->findOne(self::$TABLE, $params);
        
        if ($result ===  null){
            return null;
        }
        
        $store = new Store($result->id);
        $store->setBusinessName($result->businessName);
        $store->setTradeName($result->tradeName);
        $store->setParentAddress($result->parentAddress);
        $store->setActive($result->active);
        
        return $store;
    }
    
    public function findById(int $id):?Store
    {
        $result = $this->connection->findById(self::$TABLE, $id);
        if ($result === null){
            return null;
        }
        
        $store = new Store($result->id);
        $store->setBusinessName($result->businessName);
        $store->setTradeName($result->tradeName);
        $store->setParentAddress($result->parentAddress);
        $store->setRuc($result->ruc);
        $store->setActive($result->active);
        
        return $store;
    }
}
