<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    require('../smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    
    require_once('../app/system.class.php');
    $sys = new System();
    
     require_once '../app/PicturesHandler.class.php';
    $phandler = new PicturesHandler($sys);
    
    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
    $perms[0] = $sys->permissions_test('admin.user.create');
    $perms[1] = $sys->permissions_test('admin.user.read');
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
    $perms[6] = $sys->permissions_test('admin.picture.read');
    $perms[7] = $sys->permissions_test('application.picture.upload');
    
    $smarty->assign('perms', $perms);
    
    if(isset($_GET['img'])){
        //récupérer l'image avec l'ID
        $image = $phandler->pictures_getByID($_GET['img']);
        var_dump($_GET['img']);
        $smarty->assign('imageID', $_GET['img']);
        $smarty->display('apercuPhoto.tpl');
    }
    
?>
