<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dao;

use Domain\Bill;
use Infraestructure\Connection\Connection;

/**
 * Description of BillDao
 *
 * @author mauit
 */
class BillDao {
    private static $TABLE = "bill";
    private $connection;
    private $storeId;
    private $buyerId;
    private $voucherTypeId;

    function getStoreId():int {
        return $this->storeId;
    }

    function getBuyerId():int {
        return $this->buyerId;
    }
    
    function getVoucherTypeId():int{
        return $this->voucherTypeId;
    }

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function toArray(Bill $bill):array{
        $arr = [];
        $arr['id'] = $bill->getId();
        $arr['accessKey'] = $bill->getAccessKey();
        $arr['establishment'] = $bill->getEstablishment();
        $arr['emissionPoint'] = $bill->getEmissionPoint();
        $arr['sequential'] = $bill->getSequential();
        $arr['dateOfIssue'] = $bill->getDateOfIssue()->format("Y-m-d");
        $arr['establishmentAddress'] = $bill->getEstablishmentAddress();
        $arr['totalWithoutTax'] = $bill->getTotalWithoutTax();
        $arr['totalDiscount'] = $bill->getTotalDiscount();
        $arr['tip'] = $bill->getTip();
        $arr['total'] = $bill->getTotal();
        $arr['filePath'] = $bill->getFilePath();
        $arr['storeId'] = $bill->getStore()->getId();
        $arr['active'] = $bill->isActive();
        $arr['voucherTypeId'] = $bill->getVoucherType()->getId();
        $arr['buyerId'] = $bill->getBuyer()->getId();
        return $arr;
    }
    
    public function insert(Bill $bill):int{
        $bill->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($bill));
    }
    
    public function update(Bill $bill):int{
        return $this->connection->update(self::$TABLE, $this->toArray($bill));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
    
    public function findOne($property, $value):?Bill
    {
        if (null === $result = $this->connection->findOne(self::$TABLE, [$property=>$value]) ){
            $this->buyerId = null;
            $this->storeId = null;
            $this->voucherTypeId = null;
            return null;
        }
        
        $this->buyerId = $result->buyerId;
        $this->storeId = $result->storeId;
        $this->voucherTypeId = $result->voucherTypeId;
        
        $bill = new Bill();
        $bill->setId($result->id);
        $bill->setAccessKey($result->accessKey);
        $bill->setEmissionPoint($result->emissionPoint);
        $bill->setEstablishment($result->establishment);
        $bill->setSequential($result->sequential);
        $bill->setDateOfIssue(new \DateTime($result->dateOfIssue));
        $bill->setEstablishmentAddress($result->establishmentAddress);
        $bill->setTotalWithoutTax($result->totalWithoutTax);
        $bill->setTotalDiscount($result->totalDiscount);
        $bill->setTip($result->tip);
        $bill->setTotal($result->total);
        $bill->setFilePath($result->filePath);
        return $bill;
    }
}
