<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infraestructure\Connection;

use phpDocumentor\Reflection\Types\String_;

/**
 * Description of ConnectionMySql
 *
 * @author mauit
 */
class ConnectionMySql implements Connection {

    private $connection;

    public function __construct() {
        $dsn = "mysql:dbname=householdaccounting_v1;host=localhost";
        $username = "root";
        $password = "admin";
        try {
            $this->connection = new \PDO($dsn, $username, $password, array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ));
        } catch (\PDOException $ex) {
            echo "Falló la conexión: " . $ex->getMessage() . PHP_EOL;
            print_r($ex);
            die();
        }
    }

    public function delete(string $table, int $id): int {
        $statement = "DELETE FROM `$table` WHERE id = :id";
        $stmt = $this->connection->prepare($statement);
        $stmt->bindParam('id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function find(string $table, array $params):? array {
        $statement = "SELECT * FROM `$table` WHERE ";
        $separator = '';
        foreach ($params as $field => $param){
            $statement .= "$separator $field = :$field ";
            $separator = ' AND ';
        }
        $stmt = $this->connection->prepare($statement);
        foreach ($params as $field => $param){
            $$field = $param;
            $stmt->bindParam($field, $$field);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        if (count($result) === 0){
            return null;
        }
        return $result;
    }

    public function findById(string $table, int $id): ?\stdClass {
        $statement = "SELECT * FROM `$table` WHERE id = :id";
        $stmt = $this->connection->prepare($statement);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        if (count($result) === 0) {
            return null;
        }

        return current($result);
    }

    public function findOne(string $table, array $params): ?\stdClass {
        $statement = "SELECT * FROM `$table` WHERE ";
        $separator = "";
        foreach ($params as $param => $value) {
            $statement .= "$separator $param = :$param";
            $separator = " AND ";
        }
        $stmt = $this->connection->prepare($statement);
        foreach ($params as $param => $value) {
            $stmt->bindParam(":$param", $$param);
            $$param = $value;
        }

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new \Exception("findOne to '$table' returns more than one records.");
        }

        return current($result);
    }

    public function insert(string $table, array $params): int {
        $params['active'] = $params['active'] ? 1 : 0;
        $statement = "INSERT INTO `$table` SET ";
        $separator = '';
        foreach ($params as $column => $param) {
            $statement .= "$separator $column = :$column ";
            $separator = ",";
        }

        $stmt = $this->connection->prepare($statement);

        foreach ($params as $column => $param) {
            $stmt->bindParam(":$column", $$column);
            $$column = $param;
        }

        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    public function update(string $table, array $params): int {
        $params['active'] == true ? 1 : 0;
        $statement = "UPDATE `$table` SET ";
        $separator = '';
        foreach ($params as $column => $param) {
            if ($column !== 'id') {
                $statement .= "$separator $column = :$column ";
                $separator = ",";
            }
        }
        $statement .= " WHERE id = :id ";

        $stmt = $this->connection->prepare($statement);

        foreach ($params as $column => $param) {
            $stmt->bindParam(":$column", $$column);
            $$column = $param;
        }
        $stmt->execute();
        $rowsUpdated = $stmt->rowCount();

        return $rowsUpdated;
    }

    public function beginTransaction() {
        $this->connection->beginTransaction();
    }

    public function commit() {
        $this->connection->commit();
    }

    public function rollBack() {
        $this->connection->rollBack();
    }

    public function getErrorMessage():string{
        return $this->connection->errorInfo()[2] === null ? '' : $this->connection->errorInfo()[2];
    }

    public function executeStatement(string $statement, array $params)
    {
        
        $stmt = $this->connection->prepare($statement);
        foreach ($params as $param => $value) {
            $stmt->bindParam(":$param", $$param);
            $$param = $value;
        }

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return $result;
    }
}
