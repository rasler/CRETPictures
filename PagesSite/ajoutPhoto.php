<?php
/**
 * Page permettant d'uploader une photo
 *
 * @author Madeleine
 */
    require('../smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    $smarty->display('ajoutPhoto.tpl');
    
    if(isset($_GET['do']) && $_GET['do'] == 'ajout'){
        require_once('../app/system.class.php');
        $sys = new System();

        require_once('../app/PicturesHandler.class.php');
        $phandler = new PicturesHandler($sys);
        
        $photo = $_FILES['photoFile']['tmp_name'];

        if(isset($_POST['titlePic']) && $_POST['titlePic'] != ""){
            $phandler->pictures_upload($_POST['titlePic'], $photo);
        }
        else{
            $phandler->pictures_upload($_FILES['photoFile']['name'], $photo);
        }
    }
?>
