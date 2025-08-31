<?php
namespace Domain;

use Lib\DomainModel;

final class Buyer extends DomainModel{
    private $identificationType;
    private $name;
    private $identification;
    
    public function __construct(int $id = 0) {
        parent::__construct($id);
    }
    
    function getIdentificationType(): string {
        return $this->identificationType;
    }

    function getName(): string {
        return $this->name;
    }

    function getIdentification(): string {
        return $this->identification;
    }

    function setIdentificationType(string $identificationType): void {
        $this->identificationType = $identificationType;
    }

    function setName(string $name): void {
        $this->name = $name;
    }

    function setIdentification(string $identification): void {
        $this->identification = $identification;
    }

    function toDto(){
        $dto = new \stdClass();
        $dto->id = $this->getId();
        $dto->identificationType = $this->getIdentificationType();
        $dto->name = $this->getName();
        $dto->ruc = $this->getIdentification();
        return $dto;
    }
    
}