<?php

require_once dirname(__FILE__).'/LocalSettings.php';

class System
{
    /* Users  */
    public function user_create($user)
    {
        
    }
    public function user_getByID($id)
    {
    }
    public function user_getByLogin($login)
    {
        global $pi_db_host, $pi_db_user, $pi_db_pass, $pi_db_name, $pi_db_prefix;
        $dbh = new PDO('mysql:host='.$pi_db_host.';dbname='.$pi_db_name,$pi_db_user,$pi_db_pass);
        $rs = $dbh->prepare('SELECT uid as id, login, creation, lastConnection FROM '.$pi_db_prefix.'users WHERE login = ? LIMIT 0,1');
        $rs->execute(array($login));
        return $rs->fetch(PDO::FETCH_NAMED);
    }
    public function user_getAll()
    {
        global $pi_db_host, $pi_db_user, $pi_db_pass, $pi_db_name, $pi_db_prefix;
        $dbh = new PDO('mysql:host='.$pi_db_host.';dbname='.$pi_db_name,$pi_db_user,$pi_db_pass);
        $rs = $dbh->query('SELECT uid as id, login, creation, lastConnection FROM '.$pi_db_prefix.'users');
        return $rs->fetchAll(PDO::FETCH_NAMED);
    }
    public function user_update($user)
    {
        
    }
    public function user_delete($user)
    {
        
    }
    
    /* Session */
    public function login($login, $password)
    {
        
    }
    public function logout()
    {
        
    }
}
?>
