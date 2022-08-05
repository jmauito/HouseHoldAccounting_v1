<?php

namespace Test\Domain;

use Dao\BillAdditionalInformationDao;
use Domain\Bill;
use Domain\BillAdditionalInformation;
use Domain\BillDeductible;
use Domain\BillDetail;
use Domain\BillExpense;
use Domain\Buyer;
use Domain\Store;
use Domain\VoucherType;

class BillMother
{
    public function build($billId):Bill{

        $bill = new Bill($billId);
        $bill->setAccessKey('111');
        $bill->setEstablishment('001');
        $bill->setEmissionPoint('001');
        $bill->setSequential('1');
        $bill->setDateOfIssue(new \DateTime('2022-06-07'));
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

    /**
     * Build the Bill whit information from testBill.xml
    */
    public function buildTestBill(){
        $bill = new Bill();
        $bill->setAccessKey('2807201801019007200200120019020002250154986567417');
        $bill->setEstablishment('001');
        $bill->setEmissionPoint('902');
        $bill->setSequential('000225015');
        $bill->setDateOfIssue(new \DateTime('2018-07-28'));
        $bill->setEstablishmentAddress('AV DE LA AMERICAS Y NICOLAS DE ROCHA');
        $bill->setTotalWithoutTax(32.00);
        $bill->setTotalDiscount(1.48);
        $bill->setTip(0.00);
        $bill->setTotal(35.84);

        $store = new Store();
        $store->setBusinessName('GERARDO ORTIZ E HIJOS CIA LTDA');
        $store->setTradeName('GERARDO ORTIZ E HIJOS CIA LTDA');
        $store->setRuc('0190072002001');
        $store->setParentAddress('AV. CARLOS JULIO AROSEMENA S/N');
        $bill->setStore($store);

        $buyer = new Buyer();
        $buyer->setName('MAURICIO ZARATE');
        $buyer->setIdentification('0103294674');
        $buyer->setIdentificationType('05');
        $bill->setBuyer($buyer);

        $voucherType = new VoucherType(1);
        $voucherType->setName('bill');
        $voucherType->setCode('01');
        $bill->setVoucherType($voucherType);


        $bill->addBillDetail($this->buildTestBillDetail('4G4459X','TUBO ESTRI D/CORTINA CAFE 1/2quot; X 3.2 MTS 2738',1,2.8856,0.23,2.65));
        $bill->addBillDetail($this->buildTestBillDetail('2T23168','SOPORTE DOBLE 1/2quot; CAFE 2 SETS',2,0.9756,0.2,1.76));
        $bill->addBillDetail($this->buildTestBillDetail('2T23122','TERMINAL NORMAL 1/2quot; CAFE 6 SETS',1,.4634,0.03,0.43));
        $bill->addBillDetail($this->buildTestBillDetail('4M91L2F','LINTERNA RECARGABLE ECOLED YJ-2891',1,3.3292,0.27,3.06));
        $bill->addBillDetail($this->buildTestBillDetail('X400282','HALADOR BRONCE 3/4',1,1.053,0.03,1.02));
        $bill->addBillDetail($this->buildTestBillDetail('X9125X1','CHALECO WA014-1AK2776-1 NICOLAS',1,22.9513,0.69,22.26));
        $bill->addBillDetail($this->buildTestBillDetail('X4002A5','ARGOLLA BRONCE 3/4quot;',12,0.0703,0.03,0.82));

        $bill->addBillAdditionalInformation($this->buildBillAdditionalInformation('Lugar Venta','CORAL CENTRO') );
        $bill->addBillAdditionalInformation($this->buildBillAdditionalInformation('Codigo','6139230') );
        $bill->addBillAdditionalInformation($this->buildBillAdditionalInformation('Direccion','HORTENCIA MATA Y EL RETORNO') );
        $bill->addBillAdditionalInformation($this->buildBillAdditionalInformation('Telefonos','0989975024') );
        $bill->addBillAdditionalInformation($this->buildBillAdditionalInformation('Correo','mauricio.zarate@outlook.com') );
        $bill->addBillAdditionalInformation($this->buildBillAdditionalInformation('Deducible Vestimenta','22.26') );

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

    private function buildTestBillDetail(string $mainCode, string $description, float $quantity, float $unitPrice, float $discount, float $totalPriceWithoutTaxes ):BillDetail{
        $billDetail = new BillDetail();
        $billDetail->setMainCode($mainCode);
        $billDetail->setDescription($description);
        $billDetail->setQuantity($quantity);
        $billDetail->setUnitPrice($unitPrice);
        $billDetail->setDiscount($discount);
        $billDetail->setTotalPriceWithoutTaxes($totalPriceWithoutTaxes);
        return $billDetail;
    }

    /**
     * @return void
     */
    public function buildBillAdditionalInformation(string $name, string $value): BillAdditionalInformation
    {
        $billAdditionalInformation = new BillAdditionalInformation();
        $billAdditionalInformation->setName($name);
        $billAdditionalInformation->setValue($value);
        return $billAdditionalInformation;
    }


}