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
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function toArray(Bill $bill):array{
        $arr = [];
        $arr['id'] = $bill->getId();
        $arr['accessKey'] = $bill->getAccessKey();
        $arr['establishment'] = $bill->getEstablishment();
        $arr['emissionPoint'] = $bill->getEmissionPoint();
        $arr['secuential'] = $bill->getSecuential();
        $arr['dateOfIssue'] = $bill->getDateOfIssue();
        $arr['establishmentAddress'] = $bill->getEstablishmentAddress();
        $arr['totalWithoutTax'] = $bill->getTotalWithoutTax();
        $arr['totalDiscount'] = $bill->getTotalDiscount();
        $arr['tip'] = $bill->getTip();
        $arr['total'] = $bill->getTotal();
        $arr['filePath'] = $bill->getFilePath();
        $arr['storeId'] = $bill->getStore()->getId();
        $arr['active'] = $bill->isActive();
        $arr['voucherTypeId'] = $bill->getVoucherType()->getId();
        $arr['buyerId'] = 1;
        return $arr;
    }
    
    public function insert(Bill $bill){
        $bill->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($bill));
    }
    
    public function update(Bill $bill){
        return $this->connection->update(self::$TABLE, $this->toArray($bill));
    }
    
    public function delete(int $id):int{
        return $this->connection->delete(self::$TABLE, $id);
    }
}
