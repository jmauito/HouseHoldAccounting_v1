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
class TotalDeductiblesDao {
    private static $TABLE = "store";
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function getTotalByYear(int $year){
        $statement = "SELECT d.id 'deductibleId', 
            d.name 'deductibleName', sum(bd.value) as deductibleTotal 
            FROM bill b 
            INNER JOIN bill_deductible bd ON bd.billId = b.id AND bd.active
            INNER JOIN deductible d on d.id = bd.deductibleId AND d.active
            WHERE YEAR(b.dateOfIssue) = :year AND b.active
            GROUP BY bd.deductibleId;";
        return $this->connection->executeStatement($statement, ['year'=>$year]);
    }
}
