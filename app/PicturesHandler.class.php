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
        $this->system->permissions_require("application.picture.upload");
        $user = $this->system->current_user();
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'pictures (uid, file, size, title, publication, creation) VALUES(?,?,?,?,NOW(),?)');
        //$meta = exif_read_data($tmpFile, 'IFD0', 0); bug : n'arrive pas à trouver l'extantion exif en local ?
        $rs->execute(array($user["id"], $fullName, filesize($tmpFile), basename($fullName), isset($meta["DateTimeOriginal"])?$meta["DateTimeOriginal"]:null));
        $pid = $this->db->lastInsertId();
        if(!is_dir(dirname($this->path.$user["login"].'/'.$fullName)))
            mkdir (dirname($this->path.$user["login"].'/'.$fullName));
        rename($tmpFile, $this->path.$user["login"].'/'.$fullName);
        return $pid;
    }
    
    public function pictures_getByUserID($uid)
    {
        $login = null;
        $user = $this->system->current_user();
        if($user != null && $user["id"] == $uid)
            $login = $user["login"];
        else
        {
            $this->system->permissions_require("application.admin.picture.read");
            $rs = $this->db->prepare('SELECT login FROM '.$this->prfx.'users WHERE uid=?');
            $rs->execute(array($uid));
            if($rs->rowCount() != 1)
                throw new Exception("User not found", 404);
            $user = $rs->fetch(PDO::FETCH_NAMED);
            $login = $user["login"];
        }
        
        $rs = $this->db->prepare('SELECT pid, file, size, public, title, publication, creation FROM '.$this->prfx.'pictures WHERE uid=?');
        $rs->execute(array($uid));
        $pictures = $rs->fetchAll(PDO::FETCH_NAMED);
        
        return $this->recursivePicturesAnswer("", $pictures, $this->path.$user["login"]);
    }
    
    private function recursivePicturesAnswer($dir, $pic, $root)
    {
        $ret = array();
        $i = 0;
        
        // ne pas lire si le dossier n'existe pas
        if(!is_dir($root.$dir))
            return $ret;
        
        // lecture des dossiers
        if($dossier = opendir($root.$dir))
        {
            while(false !== ($fichier = readdir($dossier)))
            {
                if(is_dir($root.$dir.'/'.$fichier)&&$fichier!="."&&$fichier!="..")
                {
                    $ret[$i]["content"] = $this->recursivePicturesAnswer($dir.'/'.$fichier, $pic, $root);
                    $ret[$i]["type"] = "folder";
                    $ret[$i]["name"] = $fichier;
                    $i++;
                }
            }
        }
        
        // lecture des images (depuis la réponse de la BDD)
        foreach($pic as $p)
        {
            if($dir == "") $dir = ".";
            if('/'.dirname($p["file"]) == $dir)
            {
                $ret[$i] = $p;
                $ret[$i]["type"] = "picture";
                $ret[$i]["name"] = basename($p["file"]);
                $i++;
            }
        }
        return $ret;
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
