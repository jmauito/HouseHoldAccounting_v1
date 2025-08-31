<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DomainService;

/**
 * Description of DeductibleFinderService
 *
 * @author mauit
 */

use \Infraestructure\Connection\Connection;
use \Dao\DeductibleDao;

class DeductibleFinderService {
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function findAll(){
        $deductibleDao = new DeductibleDao($this->connection);
        $deductibles = $deductibleDao->find();
        return $deductibles;
    }
    
}
