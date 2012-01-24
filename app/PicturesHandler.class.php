<?php

require_once dirname(__FILE__).'/system.class.php';

class PicturesHandler
{
    private $system;
    
    public function __construct($system)
    {
        if(!$system instanceof System)
            throw new RuntimeException ("A reference to the System wad needed");
    }
}

?>
