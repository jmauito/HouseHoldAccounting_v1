<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infraestructure\Connection;

/**
 *
 * @author mauit
 */
interface Connection {
    
    public function findById(string $table, int $id):? \stdClass;
    public function findOne(string $table, array $params):? \stdClass;
    public function find(string $table, array $params):? array;
    public function insert(string $table, array $params):int;
    public function update(string $table, array $params):int;
    public function delete(string $table, int $id):int;
    public function getErrorMessage();
    public function beginTransaction();
    public function commit();
    public function rollback();
    public function executeStatement(string $statement, array $params);
}
