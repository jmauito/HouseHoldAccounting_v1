<?php
namespace Test;

#use Test\ApplicationService\CrudVoucherTypeServiceTest;
use Test\ApplicationService\RegisterBillServiceTest;
use Test\ApplicationService\ReadXmlBillServiceTest;
use Test\ApplicationService\registerDeductibleServiceTest;
use Infraestructure\Connection\ConnectionMySqlTest;

include '../vendor/autoload.php';

#$crudVoucherTypeServiceTest = new CrudVoucherTypeServiceTest();
#$crudVoucherTypeServiceTest();

echo "Creating connection... \n";
$connection = new ConnectionMySqlTest();

#echo "Register deductible test... \n";
#$registerDeductibleServiceTest = new registerDeductibleServiceTest($connection);
#$registerDeductibleServiceTest();

echo "Register bill test... \n";
$registerBillServiceTest = new RegisterBillServiceTest();
$registerBillServiceTest();

#echo "Read XML bill and save... \n";
#$readBillServiceTest = new ReadXmlBillServiceTest($connection);
#$readBillServiceTest();

#echo "Register bill with bill detail deductible";
#$registerBillServiceTest = new RegisterBillServiceTest();
#$registerBillServiceTest->registerBillWithBillDetailDeductible();


#echo "Drop test database";
#$connection->dropDataBase();