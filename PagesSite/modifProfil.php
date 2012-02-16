<?php
/**
 * Description of modifProfil:
 * Page de modification d'un profil
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
    
    if($sys->current_user() != null){
        $usr = $sys->current_user();
        $smarty->assign('name', $usr['login']);
    }
    else{
        $smarty->assign('name', "");
    }
    
    if(isset($_GET['profil'])){
        $profile = $profiles->profiles_getByID($_GET['profil']);
        $smarty->assign('profil', $profile);
        $smarty->assign('profilID', $_GET['profil']);
    }
    
    if(isset($_GET['do']) && $_GET['do'] == "update"){
        echo "modification en cours";
    }
    
    $smarty->assign('perms', $perms);
    $smarty->display('modifProfil.tpl');
?>
