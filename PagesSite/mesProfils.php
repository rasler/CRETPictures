<?php
/**
 * Description of monProfil:
 * Page de visualisation d'un profil
 *
 * @author Madeleine
 */
    require('../smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    require_once('../app/system.class.php');
    $sys = new System();
    require_once('../app/ProfilesHandler.class.php');
    $profiles = new ProfilesHandler($sys);
    
    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
    $perms[0] = $sys->permissions_test('admin.user.create');
    $perms[1] = $sys->permissions_test('admin.user.read');
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
    $perms[4] = $sys->permissions_test('admin.picture.read');
    $perms[5] = $sys->permissions_test('application.picture.upload');
    
    $smarty->assign('perms', $perms);
    
    if($sys->current_user() != null){
        $usr = $sys->current_user();
        $smarty->assign('name', $usr['login']);
    }
    else    $smarty->assign('name', "");
    
    if(isset($_GET['suppProfil'])) $profiles->profiles_delete ($_GET['suppProfil']);        
    
    $listprofils = $profiles->profiles_getAll();
    $smarty->assign('profils', $listprofils);
    
    $smarty->display('mesProfils.tpl');
?>
