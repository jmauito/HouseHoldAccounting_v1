<?php
namespace Domain;

use Lib\DomainModel;

final class VoucherType extends DomainModel{
    private $name;
    private $code;
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getName(): string {
        return $this->name;
    }

    function getCode():string {
        return $this->code;
    }

    function setName(string $name): void {
        $this->name = $name;
    }

    function setCode(string $code): void {
        if (strlen($code) !== 2){
            throw new \Exception("La longitud de voucher code debe ser de dos caracteres");
        }
        $this->code = $code;
    }

    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->name = $this->getName();
        $dto->code = $this->getCode();
        return $dto;
    }
    
}