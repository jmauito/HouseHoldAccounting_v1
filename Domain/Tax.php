<?php
namespace Domain;

use Lib\DomainModel;

final class Tax extends DomainModel {
    private $name;
    private $code;

    public function getName() : string {
        return $this->name;
    }

    public function getCode() : string {
        return $this->code;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setCode(string $code): void {
        $this->code = $code;
    }

    public function toDto():\stdClass{
        $obj = new \stdClass();
        $obj->id = $this->getId();
        $obj->name = $this->getId();
        $obj->code = $this->getId();
        return $obj;

    }

    public function toJson(){
        return json_encode($this->toDto());
    }
}