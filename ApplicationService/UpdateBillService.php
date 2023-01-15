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
use Dao\BillExpenseDao;

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
            $billDeductibles = $billDeductibleDao->findByBill();

            foreach ($billDeductibles as $billDeductible) {
                $billDeductibleDao->delete($billDeductible->getId());
            }

            foreach ($bill->getBillDeductibles() as $billDeductible) {
                if ($billDeductible->getValue() != 0) {
                    $billDeductibleDao->insert($billDeductible);
                }
            }
            
            $billExpenseDao = new BillExpenseDao($this->connection, $bill->getId());
            $billExpenses = $billExpenseDao->findByBill();

            foreach ($billExpenses as $billExpense) {
                $billExpenseDao->delete($billExpense->getId());
            }

            foreach ($bill->getBillExpenses() as $billExpense) {
                if ($billExpense->getValue() != 0) {
                    $billExpenseDao->insert($billExpense);
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
