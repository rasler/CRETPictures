<?php
/*
 * Description of apercuPhoto:
 * Page d'affichage d'une photo avec ses informations
 *
 * @author Madeleine
 */
    require('../smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    require_once('../app/system.class.php');
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
    
    $smarty->assign('perms', $perms);
    
    if(isset($_GET['img'])){
        //récupérer l'image avec l'ID
        $image = $phandler->pictures_getByID($_GET['img']);
        
        if(isset($_GET['do']) && $_GET['do'] == 'modify'){  //cas où l'utilisateur veut modifier les infos
            $smarty->assign('image', $image);
            $smarty->assign('imageID', $_GET['img']);
            $smarty->display('modifPhoto.tpl');
        }
        elseif(isset($_GET['do']) && $_GET['do'] == 'validate' && $_GET['modif']){  //cas où l'utilisateur a validé les modifs
            if(isset($_POST['title'])){
                $length = strlen($image['file'])-strlen($image['title']);
                
                //vérification de l'extension
                $extension = strrchr($_POST['title'], '.');
                
                if($extension == FALSE || 
                        ($extension != 'jpg' && $extension != 'png' && $extension != 'gif' && $extension != 'bmp')){
                    $extensionInit = strrchr($image['title'], '.');
                    echo $_POST['title'].$extensionInit;
                    $image['title'] = $_POST['title'].$extensionInit;
                }
                else    $image['title'] = $_POST['title'];
                
                //chemin de la photo à modifier
                $path = substr($image['file'], 0, $length);
                $image['file'] = $path.$image['title'];
            }
            if(isset($_POST['public']))     $image['public'] = $_POST['public'];
            if(isset($_POST['creation']))   $image['creation'] = $_POST['creation'];
            
            $phandler->pictures_update($image);
            $smarty->assign('image', $image);
            $smarty->assign('imageID', $_GET['img']);
            $smarty->display('apercuPhoto.tpl');
        }
        else{   //cas où l'utilisateur veut consulter les infos
            $smarty->assign('image', $image);
            $smarty->assign('imageID', $_GET['img']);
            $smarty->display('apercuPhoto.tpl');
        }
    }
?>
