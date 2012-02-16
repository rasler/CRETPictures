<?php

require_once dirname(__FILE__).'/system.class.php';
require_once dirname(__FILE__).'/LocalSettings.php';

class ProfilesHandler
{
    private $system;
    private $prfx;
    private $db;
    private $user;
    
    /* GESTION DES PROFILS */
    // replace optionnal non-set values by null
    private function clear_entry(&$profile)
    {
        $chmps = array('gender', 'nickName', 'firstName', 'lastName', 'birth', 'email', 'phone', 'link');
        foreach ($chmps as $c)
        {
            if(!isset($profile[$c])||$profile[$c]=="")
                $profile[$c] = null;
        }
    }
    public function profiles_create($profile)
    {
        $this->clear_entry($profile);
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'profiles (owner, gender, nickName, firstName, lastName, birth, email, phone, link) VALUES (?,?,?,?,?,?,?,?,?)');
        $rs->execute(array($this->user["id"], $profile["gender"], $profile["nickName"], $profile["firstName"], $profile["lastName"], $profile["birth"], $profile["email"], $profile["phone"], ($profile["link"] == $this->user["id"] ? $profile["link"] : null)));
        return $this->db->lastInsertId();
    }
    public function profiles_update($profile)
    {
        $this->clear_entry($profile);
        $rs = $this->db->prepare('UPDATE '.$this->prfx.'profiles SET gender=? , nickName=? , firstName=? , lastName=? , birth=? , email=? , phone=? WHERE prid=? AND owner=?');
        if(!$rs->execute(array($profile["gender"], $profile["nickName"], $profile["firstName"], $profile["lastName"], $profile["birth"], $profile["email"], $profile["phone"], $profile["prid"], $this->user["id"])))
            throw new Exception("Erreur dans la base de données");
    }
    public function profiles_delete($prid)
    {
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'profiles WHERE prid=? AND owner=? AND owner != link');
        $rs->execute(array($prid, $this->user["id"]));
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
    public function profiles_getByID($prid)
    {
        $rs = $this->db->prepare('SELECT * FROM '.$this->prfx.'profiles WHERE prid=? AND owner = ? LIMIT 1');
        $rs->execute(array($prid, $this->user["id"]));
        if($rs->rowCount()==0)
            throw new Exception("Profile Not Found", 404);
        return $rs->fetch(PDO::FETCH_NAMED);
    }
    private function profile_clone($uid)
    {
        $rs = $this->db->prepare('SELECT * FROM '.$this->prfx.'profiles WHERE owner=? AND owner = LINK LIMIT 1');
        $rs->execute(array($uid));
        if($rs->rowCount()==0)
            throw new Exception("Profile Not Found", 404);
        $temp = $rs->fetch(PDO::FETCH_NAMED);
        return $this->profiles_create($temp);
    }
    
    /* GESTION DES INVITATIONS */
    public function invitations_send($prid, $login, $message=null)
    {
        if($this->user["login"] == $login)
            throw new Exception("Impossible to invite yourself");
        $this->system->permissions_ignore();
        $u = $this->system->user_getByLogin($login);
        $this->system->permissions_ignore(false);
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'invitations VALUES (?,?,?,1,NOW(),?)');
        if(!$rs->execute(array($this->user["id"], $u["id"], $prid, $message)))
            throw new Exception("Unable create the invatation");
    }
    public function invitations_receiveAll()
    {
        $rs = $this->db->prepare('SELECT source, date, message FROM '.$this->prfx.'invitations WHERE destination = ? AND state = 1');
        $rs->execute(array($this->user["id"]));
        return $rs->fetchAll(PDO::FETCH_NAMED);
    }
    public function invitations_refuse($source)
    {
        $rs = $this->db->prepare('UPDATE '.$this->prfx.'invitations SET state = 0 WHERE source = ? AND destination = ?');
        $rs->execute(array($source, $this->user["id"]));
    }
    // prid indique l'id du profil auquel lier l'utilisateur ; s'il n'est pas fournis, un profil est créé pour représenter cet utilisateur
    public function invitations_accept($source, $prid=null)
    {
        $this->db->beginTransaction();
        $rs = $this->db->prepare('SELECT * FROM '.$this->prfx.'invitations WHERE source = ? AND destination = ?');
        $rs->execute(array($source, $this->user["id"]));
        if($rs->rowCount()==0)
            throw new Exception("Invitation Not Found", 404);
        $invite = $rs->fetch(PDO::FETCH_NAMED);
        
        if($prid == null)
            $prid = $this->profile_clone ($invite["source"]);
        
        // création des liens
        $rs = $this->db->prepare('UPDATE '.$this->prfx.'profiles SET link = ? WHERE prid = ?');
        $rs->execute(array($source, $prid));
        $rs->execute(array($this->user["id"], $invite["profile"]));
        
        // suppression de l'invitation
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'invitations WHERE source = ? AND destination = ?');  
        $rs->execute(array($source, $this->user["id"]));
        $this->db->commit();
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
