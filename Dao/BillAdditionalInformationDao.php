<?php

namespace Dao;

use Domain\BillAdditionalInformation;
use Infraestructure\Connection\Connection;

class BillAdditionalInformationDao
{
    private static $table = "bill_additional_information";
    private $connection;
    private $billId;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getBillId():int
    {
        return $this->billId;
    }

    /**
     * @param mixed $billId
     */
    public function setBillId(int $billId): void
    {
        $this->billId = $billId;
    }

    public function toArray(BillAdditionalInformation $billAdditionalInformation):array
    {
        $arr = [];
        $arr['id'] = $billAdditionalInformation->getId();
        $arr['name'] = $billAdditionalInformation->getName();
        $arr['value'] = $billAdditionalInformation->getValue();
        $arr['active'] = $billAdditionalInformation->isActive();
        $arr['billId'] = $this->getBillId();
        return $arr;
    }

    public function insert(BillAdditionalInformation $billAdditionalInformation):int
    {
        $billAdditionalInformation->setActive(true);
        return $this->connection->insert(self::$table, $this->toArray($billAdditionalInformation));
    }

    public function update(BillAdditionalInformation $billAdditionalInformation):int
    {
        return $this->connection->update(self::$table, $this->toArray($billAdditionalInformation));
    }

    public function delete(int $id):int
    {
        return $this->connection->delete(self::$table, $id);
    }

    public function findById(int $id): ? BillAdditionalInformation
    {
        if (null === $result = $this->connection->findById(self::$table, $id) ){
            return null;
        }
        return $this->parse($result);

    }

    public function findByBillId(int $billId):? array
    {
        if (null === $result = $this->connection->find(self::$table, [
            'billId' => $billId
            ])){
            return null;
        }
        
        $listBillAdditionalInformation = [];
        foreach ($result as $item) {
            $listBillAdditionalInformation[] = $this->parse($item);
        }
        return $listBillAdditionalInformation;
    }

    /**
     * @param stdClass $result
     * @return BillAdditionalInformation
     */
    public function parse(\stdClass $result): BillAdditionalInformation
    {
        $this->setBillId($result->billId);
        $billAdditionalInformation = new BillAdditionalInformation($result->id);
        $billAdditionalInformation->setName($result->name);
        $billAdditionalInformation->setValue($result->value);
        $billAdditionalInformation->setActive($result->active);
        return $billAdditionalInformation;
    }

}