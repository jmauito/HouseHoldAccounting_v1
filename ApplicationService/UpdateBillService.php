<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationService;

/**
 * Description of UpdateBillService
 *
 * @author mauit
 */
use \Infraestructure\Connection\Connection;
use Domain\Bill;
use \Dao\BillDeductibleDao;
use Dao\BillDetailDeductibleDao;
use Dao\BillExpenseDao;
use Dao\DeductibleDao;

class UpdateBillService {

    private $connection;
    private $errors = [];

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function __invoke(Bill $bill):?bool {
        // Si las facturas son creadas desde un XML entonces lo único que se
        // debería poder modificar serían los valores de los deductibles y de
        // los gastos.
        // Si la factura fue creada manualmente, se deberá eliminar en caso de
        // querer cambiar otro dato.
        #Deductibles, aplicar la lógica de eliminar los valores anteriores y
        #crear nuevos
        try {
            $this->connection->beginTransaction();
            $billDeductibleDao = new BillDeductibleDao($this->connection, $bill->getId());
            if (null !== $billDeductibles = $billDeductibleDao->findByBill() ) {
                foreach ($billDeductibles as $billDeductible) {
                    $billDeductibleDao->delete($billDeductible->getId());
                }
    
                foreach ($bill->getBillDeductibles() as $billDeductible) {
                    if ($billDeductible->getValue() != 0) {
                        $billDeductibleDao->insert($billDeductible);
                    }
                }
            }
            
            $billExpenseDao = new BillExpenseDao($this->connection, $bill->getId());
            if (null !== $billExpenses = $billExpenseDao->findByBill() ){
                foreach ($billExpenses as $billExpense) {
                    $billExpenseDao->delete($billExpense->getId());
                }
    
                foreach ($bill->getBillExpenses() as $billExpense) {
                    if ($billExpense->getValue() != 0) {
                        $billExpenseDao->insert($billExpense);
                    }
                }
            }
            
            foreach($bill->getBillDetails() as $billDetail){
                $billDetailDeductibleDao = new BillDetailDeductibleDao($this->connection, $billDetail->getId());
                $billDetailDeductible = $billDetailDeductibleDao->findByBillDetail();
                if($billDetail->getBillDetailDeductible() !== null){
                    if(null === $billDetailDeductible){
                        $deductibleDao = new DeductibleDao($this->connection);
                        $billDetail->getBillDetailDeductible()->setDeductible( $deductibleDao->findById($billDetail->getBillDetailDeductible()->getDeductibleId()) );
                        $billDetailDeductibleDao->insert($billDetail->getBillDetailDeductible());
                    } else {
                        $deductibleDao = new DeductibleDao($this->connection);
                        $deductible = $deductibleDao->findById($billDetail->getBillDetailDeductible()->getDeductibleId());
                        $billDetailDeductible->setDeductible( $deductible );
                        $billDetailDeductible->setValue($billDetail->getBillDetailDeductible()->getValue());
                        $billDetailDeductible->setDeductibleId($billDetail->getBillDetailDeductible()->getDeductibleId());
                        $billDetailDeductibleDao->update($billDetailDeductible); 
                        if (strlen($this->connection->getErrorMessage()) > 0 ) {
                            throw new \Exception($this->connection->getErrorMessage());
                        }
                    }
                } elseif($billDetailDeductible !== null) {
                    $billDetailDeductibleDao->delete($billDetailDeductible->getId());
                }
            }
            
            $this->connection->commit();
        } catch (\Exception $exc) {
            error_log($exc->getMessage());
            $this->connection->rollBack();
            $this->errors[] = $exc->getMessage();
            return null;
        }
        return true;
    }
    
    public function getErrors():?array{
        return $this->errors;
    }

}
