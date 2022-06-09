<?php

namespace Test\Domain;

use Domain\Store;

class StoreMother
{
    public function build():Store{
        $store = new Store(1);
        $store->setBusinessName("Test Store");
        $store->setTradeName("Test store .Inc");
        $store->setRuc('1111111111001');
        $store->setParentAddress("Parent Address");
        return $store;
    }
}