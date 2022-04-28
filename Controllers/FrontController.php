<?php
namespace Controllers;

class FrontController extends Controller{
    
    public function home(){
        echo $this->templates->render('index', [
            'title' => 'Main menu']);
    }
    
    public function error404(){
        echo $this->templates->render('404');
    }
}
