<?php

namespace Domain;

use Lib\DomainModel;

class BillDetailExpense extends DomainModel
{
    private $expense;
    private $value;
    private $expenseId;

    public function __construct(int $id = 0) {
        parent::__construct($id);
    }

    function getExpense(): Expense {
        return $this->expense;
    }

    function getValue():float {
        return $this->value;
    }

    function getExpenseId():int{
        return $this->expenseId;
    }

    function setExpense(Deductible $expense): void {
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
        $dto->deductible = $this->getName();
        $dto->value = $this->getValue();
        return $dto;
    }


}