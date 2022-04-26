<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationService;

/**
 * Description of SearchBillService
 *
 * @author mauit
 */

use Infraestructure\Connection\Connection;
use Dao\BillDao;
use Domain\Bill;
use Dao\StoreDao;
use Domain\Buyer;

class SearchBillService {
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function searchByAccessKey($accessKey):?Bill
    {
        $billDao = new BillDao($this->connection);
        if (null === $bill = $billDao->findOne('accessKey',$accessKey)){
            return null;
        }
        $storeDao = new StoreDao($this->connection);
        $store = $storeDao->findById($billDao->getStoreId());
        $bill->setStore($store);
        $bill->setBuyer(new Buyer());
        return $bill;
    }
    
}
