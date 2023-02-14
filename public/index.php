<?php

require '../vendor/autoload.php';

$router = new AltoRouter();

$router->map('GET', '/', 'FrontController#home', 'home');
$router->map('GET', '/load-from-xml', 'BillController#loadFromXml', 'load-from-xml');
$router->map('POST', '/create-bill-from-xml', 'BillController#createBillFromXml', 'create-bill-from-xml');
$router->map('POST', '/register-bill', 'BillController#registerBill', 'register-bill');
$router->map('GET', '/create-bill', 'BillController#createBill', 'create-bill');
$router->map('POST', '/insert-bill', 'BillController#insertBill', 'insert-bill');
$router->map('GET', '/deductibles-by-year/[i:id]', 'TotalDeductiblesByYearController#viewTotalDeductiblesByYear', 'deductibles-by-year');
$router->map('GET', '/bills/[i:id]', 'BillController#findById', 'bill-find-by-id');
$match = $router->match();

if (!$match){
    open404Error();
    die();
}
    
callController($match);

function open404Error():void
{
    header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
    $controllerObject = new Controllers\FrontController();
    $controllerObject->error404();
}

function callController(array $match):void
{
    list($controller, $action) = explode('#', $match['target']);
    $controller = 'Controllers\\' . $controller;
    if (!method_exists($controller, $action)){
        open404Error();
        die();
    }

    $controllerObject = new $controller;
    call_user_func_array([$controllerObject, $action], $match['params']);
    
}

