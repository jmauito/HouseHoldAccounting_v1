<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationService;

/**
 * Description of RegisterBillService
 *
 * @author mauit
 */
use Domain\Bill;
use Dao\BillDao;
use \Domain\Store;
use Infraestructure\Connection\Connection;

class RegisterBillService {

    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function __invoke(Bill $bill) {

        $this->connection->beginTransaction();
        try {
            $storeId = $this->getOrCreateStore($bill->getStore());
            $bill->getStore()->setId($storeId);
            $billDao = new BillDao($this->connection);
            $billId = $billDao->insert($bill);
            $this->connection->commit();
        } catch (\Exception $exc) {
            $this->connection->rollBack();
            echo $exc->getMessage();
            echo $exc->getTraceAsString();
        }

        return $billId;
    }

    private function getOrCreateStore(Store $store) {
        $storeDao = new \Dao\StoreDao($this->connection);
        $storeExist = $storeDao->findOne(['ruc' => $store->getRuc()]);
        if ($storeExist === null) {
            $storeId = $storeDao->insert($store);
        } else {
            $storeId = $storeExist->getId();
        }
        return $storeId;
    }

}
