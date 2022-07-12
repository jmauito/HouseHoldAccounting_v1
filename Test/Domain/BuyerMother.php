<?php

namespace Test\Domain;

use Domain\Buyer;
use Faker\Factory;

class BuyerMother
{
    public function build():Buyer{
        $buyer = new Buyer(1);
        $buyer->setIdentificationType(1);
        $buyer->setName("Test buyer");
        $buyer->setIdentification("9999999999");
        return $buyer;
    }
    public static function create(int $id, string $identificationType, string $name, string $identification): Buyer{
        $buyer = new Buyer($id);
        $buyer->setIdentificationType($identificationType);
        $buyer->setName($name);
        $buyer->setIdentification($identification);
        $buyer->setActive(true);
        return $buyer;
    }
    public static function random(): Buyer{
        $id = Factory::create()->randomDigit();
        $identificationType = Factory::create()->randomNumber(2);
        $name = Factory::create()->name();
        $identification = '0000000000001';
        return self::create($id, $identificationType, $name, $identification);
    }
    public static function withId(int $id): Buyer{
        $buyer = self::random();
        $buyer->setId($id);
        return $buyer;
    }
    public static function withIdentificationType(int $identificationType): Buyer{
        $buyer = self::random();
        $buyer->setIdentificationType($identificationType);
        return $buyer;
    }
    public static function withName(string $name): Buyer{
        $buyer = self::random();
        $buyer->setName($name);
        return $buyer;
    }
    public static function withIdentification(int $identification): Buyer{
        $buyer = self::random();
        $buyer->setIdentification($identification);
        return $buyer;
    }

}