<?php

require_once dirname(__FILE__).'/LocalSettings.php';
require_once dirname(__FILE__).'/lib/PermissionException.class.php';

class System
{
    private $db;
    private $prfx;
    private $perms;
    private $user;
    
    /* Users  */  
    public function user_create($login, $password)
    {
        global $default_permissions;
        if(!is_string($login)||!is_string($password)||!ctype_alnum($login))
            throw new Exception ("Invalid user");
        $this->permissions_require ("admin.user.create");
        $this->db->beginTransaction();
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'users (login, pass, creation) VALUES (?,?,NOW())');
        $rs->execute(array($login, $this->crypte_password($password)));
        $id = $this->db->lastInsertId();
        foreach($default_permissions as $perm)
            $this->db->exec('INSERT INTO '.$this->prfx.'permissions VALUES ('.$id.', \''.$perm.'\');');
        $this->db->commit();
        return $id;
    }
    public function user_getByID($id)
    {
        if(!is_int($id)&&!ctype_digit($id))
            throw new Exception ("Invalid ID");
        if(!isset($this->user["id"]) || $id != $this->user["id"])
            $this->permissions_require ("admin.user.read");
        $rs = $this->db->prepare('SELECT uid as id, login, creation, lastConnection FROM '.$this->prfx.'users WHERE uid = ? LIMIT 0,1');
        $rs->execute(array($id));
        if($rs->rowCount() == 0)
            throw new Exception("User not found", 404);
        $user = $rs->fetch(PDO::FETCH_NAMED);
        $user["permissions"] = $this->permissions_getFrom($user["id"]);
        return $user;
    }
    public function user_getByLogin($login, $password=null)
    {
        if(!is_string($login))
            throw new Exception ("Invalid login");
        $user = null;
        $rs = null;
        if(is_string($password))
        {
            $rs = $this->db->prepare('SELECT uid as id, login, creation, lastConnection FROM '.$this->prfx.'users WHERE login = ? AND pass = ? LIMIT 0,1');
            $rs->execute(array($login, $this->crypte_password($password)));
        }
        else
        {
            if(!isset($this->user["login"]) || $login != $this->user["login"])
                    $this->permissions_require ("admin.user.read");
            $rs = $this->db->prepare('SELECT uid as id, login, creation, lastConnection FROM '.$this->prfx.'users WHERE login = ? LIMIT 0,1');
            $rs->execute(array($login));
        }
        if($rs->rowCount() == 0)
            throw new Exception("User not found", 404);
        $user = $rs->fetch(PDO::FETCH_NAMED);
        $user["permissions"] = $this->permissions_getFrom($user["id"]);
        return $user;
    }
    public function user_getAll()
    {
        $this->permissions_require("admin.user.read");
        $rs = $this->db->query('SELECT uid as id, login, creation, lastConnection FROM '.$this->prfx.'users');
        return $rs->fetchAll(PDO::FETCH_NAMED);
    }
    public function user_update($user)
    {
        if(!isset($user["id"])||!isset($user["login"]))
            throw new Exception ("Invalid user");
        if(!isset($this->user["id"]) || $user["id"] != $this->user["id"])
            $this->permissions_require ("admin.user.update");
        if(isset($user["password"]))
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'users SET login = ?, pass = ?  WHERE uid = ? LIMIT 1');
            $rs->execute(array($user["login"], $this->crypte_password($user["password"]), $user["id"]));
        }
        else
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'users SET login = ?  WHERE uid = ? LIMIT 1');
            $rs->execute(array($user["login"], $user["id"]));
        }
    }
    public function user_delete($user)
    {
        if(!isset($user["id"])||!isset($user["login"]))
            throw new Exception ("Invalid user");
        if(!isset($this->user["id"]) || $user["id"] != $this->user["id"])
            $this->permissions_require ("admin.user.delete");
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'users WHERE uid = ? AND login = ? LIMIT 1');
        $rs->execute(array($user["id"], $user["login"]));
    }
    private function crypte_password($pass)
    {
        return sha1($pass);
    }
    
    /* Permissions */
    private function permissions_getFrom($id)
    {
        if(!is_int($id)&&!ctype_digit($id))
            throw new Exception ("Invalid ID");
        $rs = $this->db->prepare('SELECT perm FROM '.$this->prfx.'permissions WHERE uid = ?');
        $rs->execute(array($id));
        return $rs->rowcount() > 0 ? $rs->fetchAll(PDO::FETCH_COLUMN, 0) : array();
    }
    public function permissions_test($perm)
    {
        if(in_array($perm, $this->perms))
            return true;
        foreach ($this->perms as $value)
        {
            $tmp = strstr($value, '*', true);
            if($tmp != false && substr_compare($perm, $tmp, 0, strlen($tmp), true) == 0)
                return true;
        }
        return false;
    }
    public function permissions_require($perm)
    {
        if(!$this->permissions_test($perm))
            throw new PermissonException ($perm);
    }
    public function permissions_grant($user_id, $perm)
    {
        // the user must have the permession to grant new permission ...
        $this->permissions_require ("admin.permission.grant");
        // ...and must have the permission he desires to grant
        $this->permissions_require ($perm);
        
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'permissions VALUES(?, ?)');
        $rs->execute(array($user_id, $perm));
    }
    public function permissions_revoke($user_id, $perm)
    {
        $this->permissions_require ("admin.permission.revoke");
        $this->permissions_require ($perm);
        if($user_id == $this->user["id"])
            throw new Exception ("A user cannont revoke his own permissions");
        
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'permissions WHERE uid=? AND perm=? LIMIT 1');
        $rs->execute(array($user_id, $perm));
    }


    /* Session */
    public function login($login, $password)
    {
        $this->logout(false);
        $me = $this->user_getByLogin($login, $password);
        if($me == null)
            throw new Exception("User not found", 404);
        $_SESSION["uid"] = $me["id"];
        $this->user = $me;
        $this->perms = $me["permissions"];
        try
        {
            $this->permissions_require("application.login");
        }
        catch(PermissonException $e)
        {
            $this->logout();
            throw $e;
        }
    }
    public function logout($removeCookie=true)
    {
        global $public_permissions;
        $_SESSION = array();
        
        // suppression du cookie de la session
        if (ini_get("session.use_cookies")&&$removeCookie) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        session_start();
        $this->perms = $public_permissions;
        $this->user = null;
    }
    public function current_user()
    {
        return $this->user;
    }
    
    
    public function __construct()
    {
        global $pi_db_host, $pi_db_user, $pi_db_pass, $pi_db_name, $pi_db_prefix, $public_permissions;
        $this->db = new PDO('mysql:host='.$pi_db_host.';dbname='.$pi_db_name,$pi_db_user,$pi_db_pass);
        $this->prfx = $pi_db_prefix;
        
        if(isset($_SESSION["uid"]))
        {
            $this->user["id"] = $_SESSION["uid"];
            $this->user = $this->user_getByID($this->user["id"]);
            if($this->user == null)
                $this->logout ();
            else
                $this->perms = $this->user["permissions"];
        }
        else
            $this->perms = $public_permissions;
    }
    
    public function get_db()
    {
        return $this->db;
    }
}
?>
