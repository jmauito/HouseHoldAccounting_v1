<?php
namespace Controllers;

use ApplicationService\BillFinderService;
use Infraestructure\Connection\ConnectionMySql;

class FrontController extends Controller{
    
    public function home(){
        $billFinderService = new BillFinderService(new ConnectionMySql());
        $lastBillsRegistered = $billFinderService->findLastRegistered();
        echo $this->templates->render('index', [
            'title' => 'Main menu',
            'lastBillsRegistered' => $lastBillsRegistered
        ]);
    }
    
    public function error404(){
        echo $this->templates->render('404');
    }
}
