<?php

require_once dirname(__FILE__).'/system.class.php';
require_once dirname(__FILE__).'/LocalSettings.php';

class SearchEngine {
    private $params;
    private $system;
    private $prfx;
    private $db;
    private $user;
    
    private function shareFilter(&$args)
    {
        if($this->user == null)
            return "public = 1";
        
        $args[count($args)] = $this->user["id"];
        $args[count($args)] = $this->user["id"];
        return "(public = 1 OR owner=? OR pid IN (SELECT s.pid FROM ".$this->prfx."shares s JOIN ".$this->prfx."profiles p USING (prid) WHERE p.link = ?))";
    }
    
    public function search()
    {
        $sql = "SELECT * FROM ".$this->prfx."pictures WHERE ";
        $args = array();
        
        // application des filtres
        $sql .= $this->shareFilter($args);
        
        // lancement de la recherche
        $rs = $this->db->prepare($sql);  
        $rs->execute($args);
        
        return $rs->fetch(PDO::FETCH_NAMED);
    }
    
    public function pictures_getAll(){
        $rs = $this->db->prepare("SELECT * FROM ".$this->prfx."pictures WHERE public = 1");
        $rs->execute();
        return $rs->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function __construct($system)
    {
        global $pi_db_prefix;
        $this->system = $system;
        $this->prfx = $pi_db_prefix;
        if(!$system instanceof System)
            throw new RuntimeException ("A reference to the System wad needed");
        $this->db = $system->get_db();
        $this->user = $system->current_user();
    }
}

?>
