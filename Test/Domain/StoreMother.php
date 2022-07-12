<?php

namespace Test\Domain;

use Domain\Store;
use Faker\Factory;

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
    public static function create(int $id, string $businessName, string $tradeName, string $ruc, string $parentAddress): Store{
        $store = new Store($id);
        $store->setBusinessName($businessName);
        $store->setTradeName($tradeName);
        $store->setRuc($ruc);
        $store->setParentAddress($parentAddress);
        $store->setActive(true);
        return $store;
    }
    public static function random(): Store{
        $id = Factory::create()->randomDigit();
        $businessName = Factory::create()->company;
        $tradeName = Factory::create()->companySuffix;
        $ruc = '9999999999999';
        $parentAddress = Factory::create()->address;
        return self::create($id, $businessName, $tradeName, $ruc, $parentAddress);
    }
    public static function withId(int $id): Store{
        $store = self::random();
        $store->setId($id);
        return $store;
    }
    public static function withBusinessName(int $businessName): Store{
        $store = self::random();
        $store->setBusinessName($businessName);
        return $store;
    }
    public static function withTradeName(string $tradeName): Store{
        $store = self::random();
        $store->setTradeName($tradeName);
        return $store;
    }
    public static function withRuc(int $ruc): Store{
        $store = self::random();
        $store->setRuc($ruc);
        return $store;
    }
    public static function withParentAddress(int $parentAddress): Store{
        $store = self::random();
        $store->setParentAddress($parentAddress);
        return $store;
    }
}