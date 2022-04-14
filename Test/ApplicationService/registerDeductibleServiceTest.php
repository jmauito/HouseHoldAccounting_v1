<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\ApplicationService;

/**
 * Description of registerDeductibleServiceTest
 *
 * @author mauit
 */
use Infraestructure\Connection\Connection;
use Dao\DeductibleDao;
use Domain\Deductible;

class registerDeductibleServiceTest {
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function __destruct() {
        #$this->connection->dropDataBase();
    }
    
    public function __invoke() {
        $this->connection->createDataBase();
        $deductibleDao = new DeductibleDao($this->connection);
        $deductible = new Deductible();
        $deductible->setName("Vivienda");
        $id = $deductibleDao->insert($deductible);
        print("Se registró el deductible vivienda con id: $id");
        
    }
}
