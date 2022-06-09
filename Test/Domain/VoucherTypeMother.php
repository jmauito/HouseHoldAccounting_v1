<?php

namespace Test\Domain;

use Domain\VoucherType;

class VoucherTypeMother
{
    public function build():VoucherType{
        $voucherType = new VoucherType(1);
        $voucherType->setName('bill');
        $voucherType->setCode('01');
        return $voucherType;
    }
}