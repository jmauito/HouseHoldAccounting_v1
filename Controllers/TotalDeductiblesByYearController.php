<?php
namespace Controllers;

use ApplicationService\TotalDeductiblesByYearService;
use Infraestructure\Connection\ConnectionMySql;

class TotalDeductiblesByYearController extends Controller{
    public function viewTotalDeductiblesByYear(int $year){
        $connection = new ConnectionMySql();
        $totalDeductublesByYearService = new TotalDeductiblesByYearService($connection);
        $deductibleValueList =  $totalDeductublesByYearService->totalByYear($year) ;
        
        echo $this->templates->render('deductibles-by-year', [
            'title' => 'Total de deducibles por año',
            'year' => $year,
            'deductibleValueList' => $deductibleValueList
        ]);
    }
}