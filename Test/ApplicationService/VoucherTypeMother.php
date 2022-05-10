<?php

namespace Test\ApplicationService;

use Faker\Factory; 
use Domain\VoucherType;

final class VoucherTypeMother {
    public static function create(int $id, string $name, string $code): VoucherType{
        $voucherType = new VoucherType($id);
        $voucherType->setName($name);
        $voucherType->setCode($code);
        $voucherType->setActive(true);
        return $voucherType;
    }
    public static function random(): VoucherType{
        $id = Factory::create()->randomDigit;
        $name = Factory::create()->text();
        $code = '02';
        return self::create($id, $name, $code);
    }
    public static function withId(int $id): VoucherType{
        $voucherType = self::random();
        $voucherType->setId($id);
        return $voucherType;
    }
    public static function withName(string $name): VoucherType{
        $voucherType = self::random();
        $voucherType->setName($name);
        return $voucherType;
    }
    public static function withCode(int $code): VoucherType{
        $voucherType = self::random();
        $voucherType->setCode($code);
        return $voucherType;
    }
}
