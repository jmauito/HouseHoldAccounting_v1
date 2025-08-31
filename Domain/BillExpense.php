<?php
namespace Domain;

use Lib\DomainModel;

final class BillExpense extends DomainModel{
    private $expense;
    private $value;
    private $expenseId;
    
    function getExpense(): Expense {
        return $this->expense;
    }

    function getValue():float {
        return $this->value;
    }
    
    function getExpenseId():int{
        return $this->expenseId;
    }

    function setExpense(Expense $expense): void {
        $this->expense = $expense;
    }

    function setValue(float $value): void {
        $this->value = $value;
    }
    
    function setExpenseId(int $expenseId): void {
        $this->expenseId = $expenseId;
    }

    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->expense = $this->getExpense()->toDto();
        $dto->value = $this->getValue();
        return $dto;
    }
    
}