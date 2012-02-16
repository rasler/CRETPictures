<?php
/**
 * Description of aperçuProfil:
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
    
    if($sys->current_user() != null){
        $usr = $sys->current_user();
        $smarty->assign('name', $usr['login']);
    }
    else{
        $smarty->assign('name', "");
        $smarty->assign('profil', NULL);
    }
    
    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
    $perms[0] = $sys->permissions_test('admin.user.create');
    $perms[1] = $sys->permissions_test('admin.user.read');
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
    $perms[4] = $sys->permissions_test('admin.picture.read');
    $perms[5] = $sys->permissions_test('application.picture.upload');
    
    $smarty->assign('perms', $perms);
    
    if(isset($_GET['profil'])){
        $profile = $profiles->profiles_getByID($_GET['profil']);
        
        if(isset($_GET['do']) && $_GET['do'] == "modify"){  // cas où l'utilisateur veut modifier le profil
            $smarty->assign('profil', $profile);
            $smarty->assign('profilID', $_GET['profil']);
            
            $smarty->display('modifProfil.tpl');
        }
        elseif(isset($_GET['do']) && $_GET['do'] == "update" && $_GET['profil']){    //validation des modifs
            if(isset($_POST['gender'])) $profile['gender'] = $_POST['gender'];
            if(isset($_POST['nickname']))   $profile['nickName'] = $_POST['nickname'];
            if(isset($_POST['firstname']))  $profile['firstName'] = $_POST['firstname'];
            if(isset($_POST['lastname']))   $profile['lastName'] = $_POST['lastname'];
            if(isset($_POST['birth']))  $profile['birth'] = $_POST['birth'];
            if(isset($_POST['email']))  $profile['email'] = $_POST['email'];
            if(isset($_POST['phone']))  $profile['phone'] = $_POST['phone'];

            $profiles->profiles_update($profile);
            
            $smarty->assign('profil', $profile);
            $smarty->assign('profilID', $_GET['profil']);
            $smarty->display('apercuProfil.tpl');
        }
        else{
            $smarty->assign('profil', $profile);
            $smarty->assign('profilID', $_GET['profil']);
            $smarty->display('apercuProfil.tpl');
        }
    }
?>