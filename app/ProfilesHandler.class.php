<?php

require_once dirname(__FILE__).'/system.class.php';
require_once dirname(__FILE__).'/LocalSettings.php';

class ProfilesHandler
{
    private $system;
    private $prfx;
    private $db;
    private $user;
    
    // replace optionnal non-set values by null
    private function clear_entry(&$profile)
    {
        $chmps = array('gender', 'nickName', 'firstName', 'lastName', 'birth', 'email', 'phone', 'link');
        foreach ($chmps as $c)
        {
            if(!isset($profile[$c]))
                $profile[$c] = null;
        }
    }
    public function profiles_create($profile)
    {
        $this->clear_entry($profile);
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'profiles (owner, gender, nickName, firstName, lastName, birth, email, phone, link) VALUES (?,?,?,?,?,?,?,?,?)');
        $rs->execute(array($this->user["id"], $profile["gender"], $profile["nickName"], $profile["firstName"], $profile["lastName"], $profile["birth"], $profile["email"], $profile["phone"], ($profile["link"] == $this->user["id"] ? $profile["link"] : null)));
    }
    public function profiles_update($profile)
    {
        $this->clear_entry($profile);
        $rs = $this->db->prepare('UPDATE '.$this->prfx.'profiles SET gender=? AND nickName=? AND firstName=? AND lastName=? AND birth=? AND email=? AND phone=? WHERE prid=? AND owner=?');
        $rs->execute(array($profile["gender"], $profile["nickName"], $profile["firstName"], $profile["lastName"], $profile["birth"], $profile["email"], $profile["phone"], $profile["prid"], $this->user["id"]));
    }
    public function profiles_delete($pid)
    {
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'profiles WHERE prid=? AND owner=? AND owner != link');
        $rs->execute(array($profile["prid"], $this->user["id"]));
    }
    public function profiles_getAll()
    {
        $rs = $this->db->prepare('SELECT * FROM '.$this->prfx.'profiles WHERE owner=? AND owner != link');
        $rs->execute(array($this->user["id"]));
        return $rs->fetchAll(PDO::FETCH_NAMED);
    }
    public function profiles_getMine()
    {
        $rs = $this->db->prepare('SELECT * FROM '.$this->prfx.'profiles WHERE owner=? AND owner = link LIMIT 1');
        $rs->execute(array($this->user["id"]));
        return $rs->rowCount()==0?null:$rs->fetch(PDO::FETCH_NAMED);
    }
    
    public function __construct($system)
    {
        global $pi_data_path, $pi_db_prefix;
        $this->system = $system;
        $this->prfx = $pi_db_prefix;
        if(!$system instanceof System)
            throw new RuntimeException ("A reference to the System wad needed");
        $this->db = $system->get_db();
        $this->user = $system->current_user();
    }
}
?>
