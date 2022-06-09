<?php

namespace Test\Domain;

use Domain\Deductible;

class DeductibleMother
{
    public function build():Deductible{
        $deductible = new Deductible(1);
        $deductible->setName("Test Deductible");
        return $deductible;
    }
}