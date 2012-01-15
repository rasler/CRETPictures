<?php
require 'Slim/Slim.php';
require_once 'System.class.php';

$app = new Slim();
$system = new System();

// Exemple d'utilisation
//$app->get('/hello/:name', function ($name) {
//    echo "Hello, $name!";
//});

$app->get('/user', function () {
    global $system;
    $users = $system->user_getAll();
    echo json_encode($users);
});

$app->get('/user/:login', function ($login) {
    global $system;
    $user = $system->user_getByLogin($login);
    echo json_encode($user);
});

$app->run();
?>