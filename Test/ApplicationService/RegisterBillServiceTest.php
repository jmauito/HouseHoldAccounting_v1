<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\ApplicationService;

/**
 * Description of RegisterBillServiceTest
 *
 * @author mauit
 */


use Dao\ExpenseDao;
use ApplicationService\RegisterBillService;
use Infraestructure\Connection\ConnectionMySqlTest;
use Dao\DeductibleDao;
use Dao\VoucherTypeDao;
use PHPUnit\Framework\TestCase;
use Test\Domain\BillMother;

final class RegisterBillServiceTest extends TestCase{

    public function __construct() {
        parent::__construct();

    }

    public function testCanRegisterBillWhitDeductible() {
        $connection = new ConnectionMySqlTest();
        $billMother = new BillMother();
        $billIdExpected = 1;
        $bill = $billMother->build($billIdExpected);
        $voucherTypeDao = new VoucherTypeDao($connection);
        $voucherTypeDao->insert($bill->getVoucherType());
        $deductibleDao = new DeductibleDao($connection);
        $deductibleDao->insert($bill->getBillDeductibles()[0]->getDeductible());
        $expenseDao = new ExpenseDao($connection);
        $expenseDao->insert($bill->getBillExpenses()[0]->getExpense());
        $registerBillService = new RegisterBillService($connection);
        $billId = $registerBillService($bill);

        $this->assertEquals($billIdExpected,$billId);
        $this->isNull($registerBillService->getErrors());
    }

    public function testCanRegisterBillWhitExpense() {
        $connection = new ConnectionMySqlTest();
        $billMother = new BillMother();
        $billIdExpected = 2;
        $bill = $billMother->build($billIdExpected);
        $voucherTypeDao = new VoucherTypeDao($connection);
        $voucherTypeDao->insert($bill->getVoucherType());
        $deductibleDao = new DeductibleDao($connection);
        $deductibleDao->insert($bill->getBillDeductibles()[0]->getDeductible());
        $expenseDao = new ExpenseDao($connection);
        $expenseDao->insert($bill->getBillExpenses()[0]->getExpense());
        $registerBillService = new RegisterBillService($connection);
        $billId = $registerBillService($bill);

        $this->assertEquals($billIdExpected,$billId);
        $this->isNull($registerBillService->getErrors());
    }


}
