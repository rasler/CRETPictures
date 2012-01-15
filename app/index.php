<?php
require 'Slim/Slim.php';
$app = new Slim();

// Exemple d'utilisation
//$app->get('/hello/:name', function ($name) {
//    echo "Hello, $name!";
//});

$app->run();
?>