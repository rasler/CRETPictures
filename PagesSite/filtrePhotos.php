<?php
/**
 * Description of filtrePhotos:
 * Page de tri de photos
 *
 * @author Madeleine
 */
    require '../smarty/setup.php';
    $smarty = new Smarty_CRETPictures();
    require_once '../app/System.class.php';
    $sys = new System(); 
    require_once '../app/SearchEngine.class.php';
    $search = new SearchEngine($sys);
    require_once '../app/PicturesHandler.class.php';
    $phandler = new PicturesHandler($sys);

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
    
    $smarty->assign('perms', $perms);

    if(!isset($_GET['do'])){    //affichage initial de toutes les photos accessibles
        $listPics = $search->pictures_getAll();
        $smarty->assign('tabPics', $listPics);
    }
    else{
        $listParams = array();
        $req = array();
        $i = 0;
        
        if(isset($_POST['size']) && isset($_POST['paramSize']) && $_POST['size'] != ""){
            $listParams[$i] = array('size' => array($_POST['paramSize'], $_POST['size']));
            $req[$i] = $search->filter_size($_POST['paramSize'], $_POST['size']);
            echo 'requete = '.$req[$i];
            $i++;
        }
        if(isset($_POST['listPersonnes']) && $_POST['listPersonnes'] != ""){
            $listPersonnes = explode(', ', $_POST['listPersonnes']);
            $listParams[$i] = array();
            foreach($listPersonnes as $personne){
                $listParams[$i][] = $personne;
            }
            var_dump($listParams[$i]);
            $i++;
        }
        
        if((isset($_POST['mois']) && $_POST['mois'] != "") && (isset($_POST['annee']) && $_POST['annee'] != "")){
            $req[$i] = $search->filter_date($_POST['mois'], $_POST['annee']);
            echo 'requete = '.$req[$i];
            $i++;
        }
        elseif(isset($_POST['mois']) && $_POST['mois'] != ""){
            $listParams[$i] = array('mois', $_POST['mois']);
            var_dump($listParams[$i]);
            $i++;
        }
        elseif(isset($_POST['annee']) && $_POST['annee'] != ""){
            $listParams[$i] = array('annee', $_POST['annee']);
            var_dump($listParams[$i]);
            $i++;
        }
        
        $listPics = $search->pictures_getAll();
        $smarty->assign('tabPics', $listPics);
    }
    $smarty->display('filtrePhotos.tpl');
?>
