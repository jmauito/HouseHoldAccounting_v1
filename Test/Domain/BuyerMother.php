<?php

namespace Test\Domain;

use Domain\Buyer;

class BuyerMother
{
    public function build():Buyer{
        $buyer = new Buyer(1);
        $buyer->setIdentificationType(1);
        $buyer->setName("Test buyer");
        $buyer->setIdentification("9999999999");
        return $buyer;
    }
}