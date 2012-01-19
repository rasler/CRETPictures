<?php

require_once dirname(__FILE__).'/LocalSettings.php';

class System
{
    private $db;
    private $prfx;
    /* Users  */
    public function user_create($user)
    {
        if(!isset($user["login"])||!isset($user["password"]))
            throw new Exception ("Invalid user");
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'users (login, pass, creation) VALUES (?,?,NOW)');
        $rs->execute(array($user["login"], $this->crypte_password($user["password"])));
        return $this->db->lastInsertId();
    }
    public function user_getByID($id)
    {
        if(!is_int($id))
            throw new Exception ("Invalid ID");
        $rs = $this->db->prepare('SELECT uid as id, login, creation, lastConnection FROM '.$this->prfx.'users WHERE uid = ? LIMIT 0,1');
        $rs->execute(array($id));
        return $rs->fetch(PDO::FETCH_NAMED);
    }
    public function user_getByLogin($login)
    {
        if(!is_string($login))
            throw new Exception ("Invalid login");
        $rs = $this->db->prepare('SELECT uid as id, login, creation, lastConnection FROM '.$this->prfx.'users WHERE login = ? LIMIT 0,1');
        $rs->execute(array($login));
        return $rs->fetch(PDO::FETCH_NAMED);
    }
    public function user_getAll()
    {
        $rs = $this->db->query('SELECT uid as id, login, creation, lastConnection FROM '.$this->prfx.'users');
        return $rs->fetchAll(PDO::FETCH_NAMED);
    }
    public function user_update($user)
    {
        if(!isset($user["id"])||!isset($user["login"]))
            throw new Exception ("Invalid user");
        if(isset($user["password"]))
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'users SET login = ?, pass = ?  WHERE uid = ? LIMIT 0,1');
            $rs->execute(array($user["login"], $this->crypte_password($user["password"]), $id));
        }
        else
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'users SET login = ?  WHERE uid = ? LIMIT 0,1');
            $rs->execute(array($user["login"], $id));
        }
    }
    public function user_delete($user)
    {
        if(!isset($user["id"])||!isset($user["login"]))
            throw new Exception ("Invalid user");
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'users WHERE uid = ? AND login = ? LIMIT 0,1');
        $rs->execute(array($id, $user["login"]));
    }
    private function crypte_password($pass)
    {
        return sha1($pass);
    }
    
    /* Session */
    public function login($login, $password)
    {
        
    }
    public function logout()
    {
        
    }
    
    
    public function __construct()
    {
        global $pi_db_host, $pi_db_user, $pi_db_pass, $pi_db_name, $pi_db_prefix;
        $this->db = new PDO('mysql:host='.$pi_db_host.';dbname='.$pi_db_name,$pi_db_user,$pi_db_pass);
        $this->prfx = $pi_db_prefix;
    }
    
}
?>
