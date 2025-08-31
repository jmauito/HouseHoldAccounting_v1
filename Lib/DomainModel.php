<?php
namespace Lib;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DomainModel
 *
 * @author mauit
 */
abstract class DomainModel {
    protected $id;
    protected $active;
    
    public function __construct(int $id=0) {
        $this->id = $id;
    }
    
    function getId(): int {
        return (int) $this->id;
    }

    function isActive(): bool {
        return (bool) $this->active;
    }
    
    function setId(int $id):void{
        $this->id = $id;
    }

    function setActive($active): void {
        $this->active = (bool) $active;
    }

}
