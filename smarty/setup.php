<?php

/**
 * Description of setup
 *
 * @author Madeleine
 */
    define('SMARTY_DIR','C:/Users/Madeleine/Documents/NetBeansProjects/Smarty-3.1.7/libs/');
    require(SMARTY_DIR.'Smarty.class.php');
    
    class Smarty_CRETPictures extends Smarty {
        //constructeur
        function Smarty_CRETPictures(){
            $this->__construct();

            $this->template_dir = dirname(__FILE__).'/templates/';
            $this->compile_dir = dirname(__FILE__).'templates_c/';
            $this->config_dir = dirname(__FILE__).'configs/';
            $this->cache_dir = dirname(__FILE__).'cache/';

            $this->caching = false;
        }
    }
?>
