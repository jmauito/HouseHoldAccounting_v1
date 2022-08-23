<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\VoucherType;
use Infraestructure\Connection\Connection;

/**
 * Description of VoucherTypeDao
 *
 * @author mauit
 */
class VoucherTypeDao {

    private static $TABLE = "voucher_type";
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function toArray(VoucherType $voucherType) {
        $arr = [];
        $arr['id'] = $voucherType->getId();
        $arr['name'] = $voucherType->getName();
        $arr['code'] = $voucherType->getCode();
        $arr['active'] = $voucherType->isActive();
        return $arr;
    }

    public function insert(VoucherType $voucherType) {
        $voucherType->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($voucherType));
    }

    public function update(VoucherType $voucherType) {
        return $this->connection->update(self::$TABLE, $this->toArray($voucherType));
    }

    public function delete(int $id): int {
        return $this->connection->delete(self::$TABLE, $id);
    }

    public function findOne(array $params): ?VoucherType {
        $voucherType = new VoucherType();
        foreach ($params as $property => $value) {
            if (!property_exists($voucherType, $property)) {
                return null;
            }
            
        }
        if (null ===$result = $this->connection->findOne(self::$TABLE, $params) ){
            return null;
        }
        
        $voucherType->setId($result->id);
        $voucherType->setName($result->name);
        $voucherType->setCode($result->code);
        $voucherType->setActive($result->active);
        return $voucherType;
        
    }
    
    public function findById(int $id):?VoucherType{
        if (null === $result = $this->connection->findById(self::$TABLE, $id) ){
            return null;
        }
        $voucherType = new VoucherType($result->id);
        $voucherType->setCode($result->code);
        $voucherType->setName($result->name);
        $voucherType->setActive($result->active);
        return $voucherType;
    }

}
