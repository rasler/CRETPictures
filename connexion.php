<?php
    require_once 'app/System.class.php';
    $sys = new System(); 

    $nom = $_POST['nom'];
    $mdp = $_POST['mot_passe'];

    if($_GET["do"] == "login")
    {
        try
        {
            $sys->login($nom, $mdp);
        }
        catch(Exception $e)
        {
            echo("erreur de connexion");
            header('Location: errorcon.php'); 
            
        }
    }
    else if($_GET["do"] == "logout")
    {
        $sys = new System(); 
	$sys->logout();
    }
?>