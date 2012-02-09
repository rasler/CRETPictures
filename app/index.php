<?php
require 'Slim/Slim.php';
require_once 'System.class.php';
require_once 'PicturesHandler.class.php';

$system = new System();
$pictures = new PicturesHandler($system);
$app = new Slim();

$app->get('/picture/:id', function ($id) {
    global $pictures;
    echo json_encode($pictures->pictures_getByID($id));
});

$app->get('/picture/:id/source', function ($id) use ($app)  {
    global $pictures;
    $response = $app->response();
    $response->header('Content-Type', 'image');
    //echo imagejpeg($pictures->pictures_resize($id, $x, $y));
    $pictures->pictures_readFile($id);
});

$app->get('/picture/:id/resize/(:x)x', function ($id, $x) use ($app)  {
    global $pictures;
    $response = $app->response();
    $response->header('Content-Type', 'image/jpeg');
    echo imagejpeg($pictures->pictures_resize($id, $x, null));
});

$app->get('/picture/:id/resize/(:x)x(:y)', function ($id, $x, $y) use ($app)  {
    global $pictures;
    $response = $app->response();
    $response->header('Content-Type', 'image/jpeg');
    echo imagejpeg($pictures->pictures_resize($id, $x, $y));
});

$app->get('/picture/:id/resize/x(:y)', function ($id, $y) use ($app)  {
    global $pictures;
    $response = $app->response();
    $response->header('Content-Type', 'image/jpeg');
    echo imagejpeg($pictures->pictures_resize($id, null, $y));
});

$app->get('/picture/:id/thumb/(:x)x(:y)', function ($id, $x, $y) use ($app)  {
    global $pictures;
    $response = $app->response();
    $response->header('Content-Type', 'image/jpeg');
    echo imagejpeg($pictures->pictures_getThumb($id, $x, $y));
});

$app->delete('/picture/:id', function ($id)  {
    global $pictures;
    echo json_encode($pictures->pictures_remove($id));
});

$app->get('/user/', function () {
    global $system;
    $users = $system->user_getAll();
    echo json_encode($users);
});

$app->get('/user/:login', function ($login) {
    global $system;
    $user = $system->user_getByLogin($login);
    echo json_encode($user);
});

$app->post('/user/:login', function ($login) use ($app) {
    global $system;
    echo json_encode($system->user_create($login, $app->request()->get('password')));
});

$app->put('/user/:login', function ($login) use ($app) {
    global $system;
    $user = $system->user_getByLogin($login);
    $user["password"] = $app->request()->get('password');
    echo json_encode($system->user_update($user));
});

$app->delete('/user/:login', function ($login) {
    global $system;
    $user = $system->user_getByLogin($login);
    echo json_encode($system->user_delete($user));
});

$app->post('/user/:login/permission/:perm', function ($login, $perm) {
    global $system;
    $user = $system->user_getByLogin($login);
    echo json_encode($system->permissions_grant($user["id"], $perm));
});

$app->delete('/user/:login/permission/:perm', function ($login, $perm) {
    global $system;
    $user = $system->user_getByLogin($login);
    echo json_encode($system->permissions_revoke($user["id"], $perm));
});

$app->get('/user/:login/folder', function ($login) {
    global $system, $pictures;
    $user = $system->user_getByLogin($login);
    echo json_encode($user == null ? null : $pictures->pictures_getFolderByUserID($user["id"]));
});

$app->post('/session', function () use ($app) {
    global $system;
    echo json_encode($system->login($app->request()->get('login'), $app->request()->get('password')));
});

$app->delete('/session' , function () {
    global $system;
    echo json_encode($system->logout());
});

$app->get('/session/user' , function () {
    global $system;
    echo json_encode($system->current_user());
});

$app->get('/session/folder' , function () {
    global $system, $pictures;
    $user = $system->current_user();
    echo json_encode($user == null ? null : $pictures->pictures_getFolderByUserID($user["id"]));
});

$app->run();
?>