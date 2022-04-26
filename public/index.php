<?php

require '../vendor/autoload.php';

use League\Plates\Engine;
$templates = new Engine('../Views');

echo $templates->render('index', [
    'title' => "Menu"
]);
