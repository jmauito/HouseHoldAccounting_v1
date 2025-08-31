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
    
    public function home($year = null){
        if (!$year) {
            $year = new \DateTime();
            $year = $year->format('Y');
        }
        $billFinderService = new BillFinderService($this->connection);
        $totalDeductibleByYear = new TotalDeductiblesByYearService($this->connection);
        $lastBillsRegistered = $billFinderService->findByYear($year);
        $totalByDeductible = $totalDeductibleByYear->getTotalByYear($year);
        echo $this->templates->render('index', [
            'title' => 'Main menu',
            'lastBillsRegistered' => $lastBillsRegistered,
            'totalByDeductible' => $totalByDeductible,
            'year' => $year,
            'years' => [2023, 2024, 2025]
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
            'year' => $year
        ]);
    }
}
