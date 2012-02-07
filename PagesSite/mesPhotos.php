<?php

/*
 * Description of index:
 * Page d'affichage des dossiers de photos et photos de l'utilisateur connecté
 *
 * @author Madeleine
 */
    require('../smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    
    require_once '../app/system.class.php';
    $sys = new System();
    
    require_once '../app/PicturesHandler.class.php';
    $phandler = new PicturesHandler($sys);
    
    //aller chercher les photos de l'utilisateur connecté
    $usr = $sys->current_user();
    $photos = $phandler->pictures_getFolderByUserID($usr['id']);
    
    $smarty->assign('tabPhotos', $photos);
    $smarty->display('mesPhotos.tpl');
?>
