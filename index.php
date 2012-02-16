<?php
/**
 * Description of index:
 * Page d'accueil pour utilisateur non connecté OU connecté
 *
 * @author Madeleine
 * @modifier Estelle
 */
    require 'smarty/setup.php';
    $smarty = new Smarty_CRETPictures();
    require_once 'app/System.class.php';
    $sys = new System(); 
    require_once 'app/ProfilesHandler.class.php';
    $profiles = new ProfilesHandler($sys);
    require_once 'app/SearchEngine.class.php';
    $search = new SearchEngine($sys);
    
    //si l'utilisateur n'est pas connecté
    if($sys->current_user() == null)    $smarty->display('index.tpl');
        
    //si l'utilisateur est connecté
    else{
        require_once 'app/PicturesHandler.class.php';
        $phandler = new PicturesHandler($sys);

        //aller chercher les photos de l'utilisateur connecté
        $usr = $sys->current_user();
        
        $perms; //tableau qui stockera si l'utilisateur a certaines permissions
        $perms[0] = $sys->permissions_test('admin.user.create');
        $perms[1] = $sys->permissions_test('admin.user.read');
        $perms[2] = $sys->permissions_test('admin.user.update');
        $perms[3] = $sys->permissions_test('admin.user.delete');
        $perms[4] = $sys->permissions_test('admin.picture.read');
        $perms[5] = $sys->permissions_test('application.picture.upload');

        $smarty->assign('perms', $perms);
        
        if(isset($_GET['suppProfil']))  $profiles->profiles_delete($_GET['suppProfil']);
        if(isset($_GET['suppPic'])) $phandler->pictures_remove($_GET['suppPic']);
        $photos = $phandler->pictures_getFolderByUserID($usr['id']);
        
        $listPics = $search->pictures_getAll();
        $smarty->assign('tabPics', $listPics);
                
        $smarty->assign('tabPhotos', $photos);
        $smarty->assign('name', $usr['login']);
        $smarty->display('indexConnecte.tpl');
    }
?>