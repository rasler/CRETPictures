<?php
/**
 * Description of creerProfil:
 * Page de création de profil
 *
 * @author Madeleine
 */
    require('../smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    require_once('../app/system.class.php');
    $sys = new System();
    require_once('../app/ProfilesHandler.class.php');
    $profiles = new ProfilesHandler($sys);
    
    $usr = $sys->current_user();
    
    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
    $perms[0] = $sys->permissions_test('admin.user.create');
    $perms[1] = $sys->permissions_test('admin.user.read');
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
    $perms[4] = $sys->permissions_test('admin.picture.read');
    $perms[5] = $sys->permissions_test('application.picture.upload');
    
    $smarty->assign('perms', $perms);
    
    if($usr != null)    $smarty->assign('name', $usr['login']);
    else    $smarty->assign('name', "");
    
    if(isset($_GET['who']) && $_GET['who'] == 'self'){
        $smarty->assign('who', "self");
    }
    else    $smarty->assign('who', NULL);
    
    if(isset($_GET['do']) && $_GET['do'] == "create"){
        $profile["gender"] = $_POST['gender'];
        $profile["nickName"] = $_POST['nickname'];
        $profile["firstName"] = $_POST['firstname'];
        $profile["lastName"] = $_POST['lastname'];
        $profile["birth"] = $_POST['birth'];
        $profile["email"] = $_POST['email'];
        $profile["phone"] = $_POST['phone'];
        if(isset($_GET['link']) && $_GET['link'] == "user")
            $profile["link"] = $usr['id'];
        
        var_dump($profile);
        $prid = $profiles->profiles_create($profile);
        $smarty->assign('profil', $profile);
        $smarty->assign('profilID', $prid);
        $smarty->display('apercuProfil.tpl');
    }
    else{
        $smarty->display('creerProfil.tpl');
    }
?>
