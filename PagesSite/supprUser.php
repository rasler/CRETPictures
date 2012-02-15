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
    
    if($sys->current_user() != null){
        $usr = $sys->current_user();
        $smarty->assign('name', $usr['login']);
    }
    else    $smarty->assign('name', "");
    
    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
    $perms[0] = $sys->permissions_test('admin.user.create');
    $perms[1] = $sys->permissions_test('admin.user.read');
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
    $perms[4] = $sys->permissions_test('admin.picture.read');
    $perms[5] = $sys->permissions_test('application.picture.upload');
    
    $Login = $_GET['Login'];
    $smarty->assign('Login', $Login);
    $smarty->assign('perms', $perms);
    $smarty->display('supprUser.tpl');
    
    if(isset($_GET['do']) && $_GET['do'] == 'ajout')
    {
        $Login = $_POST['Login'];
        
        $user = $sys->user_getByLogin($Login);
        
        $sys->user_delete($user);
        
        echo ("Utilisateur " . $Login . " supprimé.");
    }
    
?>
