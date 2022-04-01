<?php
namespace Test;

#use Test\ApplicationService\CrudVoucherTypeServiceTest;
use Test\ApplicationService\RegisterBillServiceTest;
use Test\ApplicationService\ReadXmlBillServiceTest;
use Infraestructure\Connection\ConnectionMySqlTest;

include '../vendor/autoload.php';

#$crudVoucherTypeServiceTest = new CrudVoucherTypeServiceTest();
#$crudVoucherTypeServiceTest();

$connection = new ConnectionMySqlTest();

$registerBillServiceTest = new RegisterBillServiceTest();
$registerBillServiceTest();

$readBillServiceTest = new ReadXmlBillServiceTest($connection);
$readBillServiceTest();


