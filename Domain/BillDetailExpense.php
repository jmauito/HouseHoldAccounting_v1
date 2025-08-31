<?php

namespace Domain;

use Lib\DomainModel;
use Domain\Expense;

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

    function setExpense(Expense $expense): void {
        $this->expense = $expense;
    }

    function setValue(float $value): void {
        $this->value = $value;
    }

    function setExpenseId(int $expenseId): void {
        $this->expenseId = $expenseId;
    }

    function toDto():\StdClass{
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->expenseId = $this->getExpenseId();
        $dto->value = $this->getValue();
        return $dto;
    }


}