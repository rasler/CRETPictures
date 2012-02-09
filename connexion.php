<?php
/**
 * Description of connexion:
 * Page de connexion ET déconnexion
 * (dépend de la valeur de "do" qui est envoyée lors de l'appel de cette page php)
 *
 * @author Estelle
 */
    require_once 'app/System.class.php';
    $sys = new System();

    $nom = $_POST['nom'];
    $mdp = $_POST['mot_passe'];

    if($_GET["do"] == "login"){
        try{
            $sys->login($nom, $mdp);
            header('Location: index.php');
        }
        catch(Exception $e){
            require_once 'smarty/setup.php';
            $smarty = new Smarty_CRETPictures();
            
            $smarty->assign("connexion", "failed");
            $smarty->display('index.tpl');
        }
    }
    else if($_GET["do"] == "logout"){
        $sys = new System();
	$sys->logout();
        header('Location: index.php');
    }
?>