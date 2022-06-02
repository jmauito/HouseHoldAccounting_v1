<?php

namespace Dao;

use Infraestructure\Connection\Connection;
use Domain\Expense;

class ExpenseDao
{
    private static $TABLE = "expense";
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function toArray(Expense $expense):array {
        $arr = [];
        $arr['id'] = $expense->getId();
        $arr['name'] = $expense->getName();
        $arr['active'] = $expense->isActive();
        return $arr;
    }

    public function insert(Expense $expense) {
        $expense->setActive(true);
        return $this->connection->insert(self::$TABLE, $this->toArray($expense));
    }

    public function update(Expense $expense) {
        return $this->connection->update(self::$TABLE, $this->toArray($expense));
    }

    public function delete(int $id): int {
        return $this->connection->delete(self::$TABLE, $id);
    }

    public function findOne(array $params): ?Expense {
        $expense = new Expense();
        foreach ($params as $property => $value) {
            if (!property_exists($expense, $property)) {
                return null;
            }

        }
        $result = $this->connection->findOne(self::$TABLE, $params);

        $expense->setId($result->id);
        $expense->setName($result->name);
        $expense->setActive($result->active);
        return $expense;

    }

    public function find():?array{
        if( null === $result = $this->connection->find(self::$TABLE, ['active' => true])){
            return null;
        }
        $expenses = [];
        foreach ($result as $obj){
            $expense = new Expense();
            $expense->setId($obj->id);
            $expense->setName($obj->name);
            $expense->setActive($obj->active);
            $expenses[] = $expense;
        }
        return $expenses;
    }

    public function findById(int $id):?Expense{
        if (null === $result = $this->connection->findById(self::$TABLE, $id)){
            return null;
        }
        $expense = new Expense($result->id);
        $expense->setName($result->name);
        $expense->setActive($result->active);
        return $expense;
    }

}