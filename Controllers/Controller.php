<?php
namespace Controllers;

use League\Plates\Engine;

abstract class Controller {
    protected $templates;
    
    public function __construct() {
        $this->templates = new Engine('../Views');
    }
    
    
    
}
