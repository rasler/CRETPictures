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
            header('Location: errorCon.php');
        }
    }
    else if($_GET["do"] == "logout"){
        $sys = new System();
	$sys->logout();
        header('Location: index.php');
    }
?>