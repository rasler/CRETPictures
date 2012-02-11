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
    $smarty->display('ajoutUser.tpl');
    
    if(isset($_GET['do']) && $_GET['do'] == 'ajout')
    {
        $Login = $_POST['Login'];
        $Pass = $_POST['Pass'];
        
        $id = $sys->user_create($Login, $Pass);
        
        echo $id;
        
        if(isset($_POST['AdminGrant']) && $_POST['AdminGrant'] == 'on')
        {
            $permis="admin.permission.grant";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['AdminRevoke']) && $_POST['AdminRevoke'] == 'on')
        {
            $permis="admin.permission.revoke";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['PictureRead']) && $_POST['PictureRead'] == 'on')
        {
            $permis="admin.picture.read";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['UserCreate']) && $_POST['UserCreate'] == 'on')
        {    
            $permis="admin.user.create";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['UserRead']) && $_POST['UserRead'] == 'on')
        {
            $permis="admin.user.read";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['UserUpdate']) && $_POST['UserUpdate'] == 'on')
        {
            $permis="admin.user.update";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['UserDelete']) && $_POST['UserDelete'] == 'on')
        {
            $permis="admin.user.delete";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['ApplicationLogin']) && $_POST['ApplicationLogin'] == 'on')
        {
            $permis="application.login";
            $sys->permissions_grant($id, $permis);
        }
        if(isset($_POST['PictureUpload']) && $_POST['PictureUpload'] == 'on')
        {
            $permis="application.picture.upload";
            $sys->permissions_grant($id, $permis);
        }
    }
    
?>
