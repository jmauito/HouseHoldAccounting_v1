<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\ApplicationService;

/**
 * Description of RegisterStoreService
 *
 * @author mauit
 */
class RegisterStoreService {
    private $connection;
    
    public function __construct(\Infraestructure\Connection\Connection $connection) {
        $this->connection = $connection;
    }
    
    public function __invoke(\Domain\Store $store) {
        $storeDao = new \Dao\StoreDao($this->connection);
        return $storeDao->insert($store);
    }
}
