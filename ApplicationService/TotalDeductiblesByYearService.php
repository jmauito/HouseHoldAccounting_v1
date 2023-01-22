<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationService;

use Dao\TotalDeductiblesDao;
use DomainService\DeductibleFinderService;
use Infraestructure\Connection\Connection;

/**
 * Description of SearchBillService
 *
 * @author mauit
 */

 class TotalDeductiblesByYearService{
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function totalByYear(int $year){
        $totalDeductiblesDao = new TotalDeductiblesDao($this->connection);
        $deductibleFinderService = new DeductibleFinderService($this->connection);
        $totalByDeductible = $totalDeductiblesDao->getTotalByYear($year);
        $deductibles = $deductibleFinderService->findAll();
        $deductibleValues = [];
        foreach($deductibles as $deductible){
            $deductibleValue = [
                'deductibleId' => $deductible->getId(),
                'deductibleName' => $deductible->getName(),
                'deductibleTotal' => 0.00 
            ];
            $dv = array_filter($totalByDeductible, function($arr) use ($deductible){
                return $arr->deductibleId == $deductible->getId();
            });
            if (count($dv) > 0){
                $deductibleValue['deductibleTotal'] = current($dv)->deductibleTotal;
            }
            $deductibleValues[] = $deductibleValue;
        }
        
        return $deductibleValues;
        
    }
 }