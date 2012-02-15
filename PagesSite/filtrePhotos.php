<?php
/**
 * Description of filtrePhotos:
 * Page de tri de photos
 *
 * @author Madeleine
 */
    require '../smarty/setup.php';
    $smarty = new Smarty_CRETPictures();
    require_once '../app/System.class.php';
    $sys = new System(); 
    require_once '../app/SearchEngine.class.php';
    $search = new SearchEngine();
    require_once '../app/PicturesHandler.class.php';
    $phandler = new PicturesHandler($sys);

    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
    $perms[0] = $sys->permissions_test('admin.user.create');
    //$perms[1] = $sys->permissions_test('admin.user.read');
    $perms[1] = true;
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
    //$perms[4] = $sys->permissions_test('admin.picture.read');
    $perms[4] = true;
    $perms[5] = $sys->permissions_test('application.picture.upload');
    
    $smarty->assign('perms', $perms);

    if($sys->current_user() != null){
        $usr = $sys->current_user();
        $smarty->assign('name', $usr['login']);
    }
    else    $smarty->assign('name', "");
    
    $list_users = $sys->user_getAll();
    
    $smarty->display('filtrePhotos.tpl');
?>
