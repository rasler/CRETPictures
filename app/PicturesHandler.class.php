<?php

require_once dirname(__FILE__).'/system.class.php';
require_once dirname(__FILE__).'/LocalSettings.php';

class PicturesHandler
{
    private $system;
    private $path;
    private $usr_login;
    private $db;
    private $prfx;
    
    public function folders_create($fullName)
    {
        $this->system->permissions_require("application.picture.upload");
        mkdir($this->path.$this->usr_login."/".$fullName);
    }
    
    public function pictures_upload($fullName, $tmpFile)
    {
        var_dump($this->system->current_user());
        $this->system->permissions_require("application.picture.upload");
        $user = $this->system->current_user();
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'pictures (uid, file, size, title, publication, creation) VALUES(?,?,?,?,NOW(),?)');
        //$meta = exif_read_data($tmpFile, 'IFD0', 0); bug : n'arrive pas à trouver l'extantion exif en local ?
        $rs->execute(array($user["id"], $fullName, filesize($tmpFile), basename($fullName), isset($meta["DateTimeOriginal"])?$meta["DateTimeOriginal"]:null));
        $pid = $this->db->lastInsertId();
        if(!file_exists(dirname($this->path.$user["login"].'/'.$fullName)))
            mkdir (dirname($this->path.$user["login"].'/'.$fullName));
        rename($tmpFile, $this->path.$user["login"].'/'.$fullName);
        return $pid;
    }
    
    public function __construct($system)
    {
        global $pi_data_path, $pi_db_prefix;
        $this->system = $system;
        $this->path = $pi_data_path;
        $this->prfx = $pi_db_prefix;
        if(!$system instanceof System)
            throw new RuntimeException ("A reference to the System wad needed");
        $this->db = $system->get_db();
        $user = $system->current_user();
        if($user != null)
            $this->usr_login = $user["login"];
        if($system->permissions_test("application.picture.upload")&&!is_dir($this->path.$this->usr_login))
            mkdir($this->path.$this->usr_login);
    }
}

?>
