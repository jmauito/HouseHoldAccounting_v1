<?php

namespace Test\Domain;

use Domain\Expense;

class ExpenseMother
{
    public function build():Expense{
        $expense = new Expense(1);
        $expense->setName("Test expense");
        return $expense;
    }
}