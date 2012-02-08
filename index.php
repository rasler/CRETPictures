<?php
/**
 * Description of index:
 * Page d'accueil pour utilisateur non connecté OU connecté
 *
 * @author Madeleine
 */
    require('smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    
    require_once 'app/System.class.php';
    $sys = new System(); 
    
    //si l'utilisateur n'est pas connecté
    if($sys->current_user() == null)
        $smarty->display('index.tpl');
    
    //si l'utilisateur est connecté
    else{
        require_once 'app/PicturesHandler.class.php';
        $phandler = new PicturesHandler($sys);

        //aller chercher les photos de l'utilisateur connecté
        $usr = $sys->current_user();
        $photos = $phandler->pictures_getFolderByUserID($usr['id']);

        $perms; //tableau qui stockera si l'utilisateur a certaines permissions
        
        $perms[0] = $sys->permissions_test('admin.user.create');
        $perms[1] = $sys->permissions_test('admin.user.read');
        $perms[2] = $sys->permissions_test('admin.user.update');
        $perms[3] = $sys->permissions_test('admin.user.delete');
                
        $perms[6] = $sys->permissions_test('admin.picture.read');
        $perms[7] = $sys->permissions_test('application.picture.upload');

        $smarty->assign('perms', $perms);
        $smarty->assign('tabPhotos', $photos);
        $smarty->display('indexConnecte.tpl');
    }
?>