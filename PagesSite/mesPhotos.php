<?php
/*
 * Description of mesPhotos:
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
    
    $perms; //tableau qui stockera si l'utilisateur a certaines permissions
    $perms[0] = $sys->permissions_test('admin.user.create');
    $perms[1] = $sys->permissions_test('admin.user.read');
    $perms[2] = $sys->permissions_test('admin.user.update');
    $perms[3] = $sys->permissions_test('admin.user.delete');
    $perms[6] = $sys->permissions_test('admin.picture.read');
    $perms[7] = $sys->permissions_test('application.picture.upload');

    //création d'un nouveau dossier
    if(isset($_GET['saisie']) && isset($_GET['currentFolder'])){
        $fullname = substr($_GET['currentFolder'], 1).'/'.$_GET['saisie'];
        $phandler->folders_create($fullname);
    }
    
    //modification d'un dossier
    elseif(isset($_GET['current']) && isset($_GET['change']) && isset($_GET['currentFolder'])){
        $fullname = substr($_GET['currentFolder'], 1).'/'.$_GET['current'];
        $newname = substr($_GET['currentFolder'], 1).'/'.$_GET['change'];
        $phandler->folders_rename ($fullname, $newname);
    }
    
    //suppression d'un dossier
    elseif(isset($_GET['suppFolder'])){
        $phandler->folders_remove ($_GET['suppFolder'], 1);
    }
    
    //suppression d'une photo
    elseif(isset($_GET['suppPic']))
        $phandler->pictures_remove ($_GET['suppPic']);
    
    
    //récupération des dossiers de l'utilisateur à la racine
    $usr = $sys->current_user();
    $photos = $phandler->pictures_getFolderByUserID($usr['id']);
    
    
    //exploration d'un dossier
    if(isset($_GET['currentFolder'])){
        if($_GET['currentFolder'] == ""){   //on est à la racine
            echo "on est à la racine";
            //tableau qui stockera le pid des photos qu'il faudra afficher
            $pics = array();
            for($i = 0; $i < count($photos); $i++){
                if($photos[$i]['type'] == 'picture'){
                    $pics[$i]['id'] = $photos[$i]['pid'];
                    $pics[$i]['title'] = $photos[$i]['title'];
                }
            }
            
            $smarty->assign('tabPhotos', $photos);
            $smarty->assign('tabPics', $pics);
        }
        else{   //on est dans un sous-répertoire
            $dossiers = explode("/", $_GET['currentFolder']);
            
            for($i = 1; $i < count($dossiers); $i++){
                for($j = 0; $j < count($photos); $j++){
                    if($photos[$j]['name'] == $dossiers[$i]){
                        $photos = $photos[$j]['content'];
                        break;
                    }
                }
            }
            
            $pics = array();
            for($i = 0; $i < count($photos); $i++){
                if($photos[$i]['type'] == 'picture'){
                    $pics[$i]['id'] = $photos[$i]['pid'];
                    $pics[$i]['title'] = $photos[$i]['title'];
                }
            }
            
            $smarty->assign('tabPics', $pics);
            $smarty->assign('tabPhotos', $photos);
        }
        $smarty->assign('currentFolder', $_GET['currentFolder']);
    }
    
    $smarty->assign('perms', $perms);
    
    $smarty->display('mesPhotos.tpl');
?>