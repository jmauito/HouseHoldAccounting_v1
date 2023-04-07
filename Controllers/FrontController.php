<?php
namespace Controllers;

use ApplicationService\BillFinderService;
use ApplicationService\TotalDeductiblesByYearService;
use Dao\DeductibleDao;
use Infraestructure\Connection\ConnectionMySql;

class FrontController extends Controller{

    private $connection;

    public function __construct()
    {
        parent::__construct();
        $this->connection = new ConnectionMySql();
    }
    
    public function home(){
        
        $billFinderService = new BillFinderService($this->connection);
        $totalDeductibleByYear = new TotalDeductiblesByYearService($this->connection);
        $lastBillsRegistered = $billFinderService->findLastRegistered();
        $totalByDeductible = $totalDeductibleByYear->getTotalByYear(2022);
        echo $this->templates->render('index', [
            'title' => 'Main menu',
            'lastBillsRegistered' => $lastBillsRegistered,
            'totalByDeductible' => $totalByDeductible,
            'year' => 2022
        ]);

    }
    
    public function error404(){
        
        echo $this->templates->render('404');
    
    }

    public function getBillsByDeductibleIdAndYear($deductibleId, $year){

        $totalDeductibleByYear = new TotalDeductiblesByYearService($this->connection);
        $bills = $totalDeductibleByYear->getBillsByDeductibleIdAndYear($deductibleId, $year);
        $deductibleDao = new DeductibleDao($this->connection);
        $deductible = $deductibleDao->findById($deductibleId);
        echo $this->templates->render('bills-by-deductible-and-year', [
            'title' => "Bills by deductible by year",
            'deductibleName' => $deductible->getName(),
            'bills' => $bills,
            'year' => 2022
        ]);
    }
}
