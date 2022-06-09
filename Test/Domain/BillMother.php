<?php

namespace Test\Domain;

use Domain\Bill;
use Domain\BillDeductible;
use Domain\BillDetail;
use Domain\BillExpense;

class BillMother
{
    public function build():Bill{
        $billId = 1;
        $bill = new Bill($billId);
        $bill->setAccessKey('111');
        $bill->setEstablishment('001');
        $bill->setEmissionPoint('001');
        $bill->setSecuential('0000001');
        $bill->setDateOfIssue('2022-06-07');
        $bill->setEstablishmentAddress("Establishment Address");
        $bill->setTotalWithoutTax(100);
        $bill->setTotal(112);

        $voucherTypeMother = new VoucherTypeMother();
        $bill->setVoucherType($voucherTypeMother->build());

        $storeMother = new StoreMother();
        $bill->setStore($storeMother->build());

        $buyerMother = new BuyerMother();
        $bill->setBuyer($buyerMother->build());

        $expenseMother = new ExpenseMother();
        $expense = $expenseMother->build();
        $billExpense = new BillExpense(1);
        $billExpense->setExpense($expense);
        $billExpense->setValue(80);
        $billExpense->setExpenseId($expense->getId());
        $bill->addBillExpense($billExpense);

        $deductibleMother = new DeductibleMother();
        $deductible = $deductibleMother->build();
        $billDeductible = new BillDeductible(1);
        $billDeductible->setDeductible($deductible);
        $billDeductible->setDeductibleId($deductible->getId());
        $billDeductible->setValue(100);
        $bill->addBillDeductible($billDeductible);

        $this->buildBillDetail($bill);
        return $bill;
    }

    private function buildBillDetail(Bill $bill){
        $billDetail = new BillDetail(1);
        $billDetail->setMainCode('001');
        $billDetail->setDescription("product test 1");
        $billDetail->setQuantity(1);
        $billDetail->setUnitPrice(50);
        $billDetail->setTotalPriceWithoutTaxes(50);
        $bill->addBillDetail($billDetail);
    }


}