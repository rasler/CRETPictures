<?php
/**
 * Description of errorCon:
 * Page d'erreur de connexion
 *
 */
    require('smarty/setup.php');
    $smarty = new Smarty_CRETPictures();
    
    $smarty->display('errorCon.tpl');
?>