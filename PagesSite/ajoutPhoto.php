<?php
/**
 * Page permettant d'uploader une photo
 *
 * @author Madeleine
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
    $smarty->display('ajoutPhoto.tpl');
    
    if(isset($_GET['do']) && $_GET['do'] == 'ajout'){
        require_once('../app/PicturesHandler.class.php');
        $phandler = new PicturesHandler($sys);

        $photo = $_FILES['photoFile']['tmp_name'];

        if(isset($_POST['titlePic']) && $_POST['titlePic'] != ""){
            $extension = strrchr($_POST['titlePic'],".");
            if($extension == FALSE){
                echo 'extension = '.$extension;
                $POST['titlePic'] = $_POST['titlePic'].'.jpg';
                echo 'nom fichier = '.$POST['titlePic'];
                $phandler->pictures_upload($POST['titlePic'], $photo);
            }
            else
                $phandler->pictures_upload($_POST['titlePic'], $photo);
        }
        else{
            $phandler->pictures_upload($_FILES['photoFile']['name'], $photo);
        }
    }
?>
