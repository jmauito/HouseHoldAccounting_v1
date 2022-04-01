<?php
namespace Test;

#use Test\ApplicationService\CrudVoucherTypeServiceTest;
use Test\ApplicationService\RegisterBillServiceTest;
use Test\ApplicationService\ReadXmlBillServiceTest;

include '../vendor/autoload.php';

#$crudVoucherTypeServiceTest = new CrudVoucherTypeServiceTest();
#$crudVoucherTypeServiceTest();

$registerBillServiceTest = new RegisterBillServiceTest();
$registerBillServiceTest();

$readBillServiceTest = new ReadXmlBillServiceTest();
$readBillServiceTest();


