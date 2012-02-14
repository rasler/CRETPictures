<?php
/**
 * Page d'ajout d'un user
 *
 * @author Estelle
 */
    require('../smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    
    require_once('../app/system.class.php');
    $sys = new System();
    
    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
        
    $perms[0] = $sys->permissions_test('admin.user.create');
    $perms[1] = $sys->permissions_test('admin.user.read');
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
                
    $perms[6] = $sys->permissions_test('admin.picture.read');
    $perms[7] = $sys->permissions_test('application.picture.upload');
    
    $smarty->assign('perms', $perms);
    
    $users = $sys->user_getAll();
    
    $smarty->assign('users', $users);
    
    $smarty->display('LectureUser.tpl');
    
    
    
?>
