<?php

namespace Test\ApplicationService;

use Domain\Buyer;
use Domain\VoucherType;
use Domain\Store;

final class BillMother {

    public static function create(int $id,
            string $accessKey,
            string $establishment,
            string $emissionPoint,
            string $secuential,
            string $dateOfIssue,
            string $establishmentAddress,
            float $totalWithoutTax,
            float $totalDiscount,
            float $tip,
            float $total,
            string $filePath,
            VoucherType $voucherType,
            Buyer $buyer,
            Store $store
    ) {
        $bill = new \Domain\Bill($id);
        $bill->setAccessKey($accessKey);
        $bill->setEstablishment($establishment);
        $bill->setEmissionPoint($emissionPoint);
        $bill->setSecuential($secuential);
        $bill->setDateOfIssue($dateOfIssue);
        $bill->setEstablishmentAddress($establishmentAddress);
        $bill->setTotalWithoutTax($totalWithoutTax);
        $bill->setTotalDiscount($totalDiscount);
        $bill->setTip($tip);
        $bill->setTotal($total);
        $bill->setFilePath($filePath);

        $bill->setVoucherType($voucherType);
        $bill->setBuyer($buyer);
        $bill->setStore($store);
        
        return $bill;
    }

    public static function random(){
        return self::create(1,
          '001001000001',
          '001',
          '001',
          '0000001',
          '2022-01-01',
          'establishment address',
          100.1234,
          10.1234,
          0.50,
          111.0,
          'file/path/file.xml',
          VoucherTypeMother::random(),
          BuyerMother::random(),
          StoreMother::random()
                );
    }
}
