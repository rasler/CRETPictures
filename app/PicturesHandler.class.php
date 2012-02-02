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
    
    public function pictures_getFolderByUserID($uid)
    {
        $login = null;
        $user = $this->system->current_user();
        if($user != null && $user["id"] == $uid)
            $login = $user["login"];
        else
        {
            $this->system->permissions_require("admin.picture.read");
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
        
        return $this->recursivePicturesAnswer("", $pictures, $this->path.$login);
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
            if('/'.dirname($p["file"]) == $dir || ($dir == "" && dirname($p["file"]) == "."))
            {
                $ret[$i] = $p;
                $ret[$i]["type"] = "picture";
                $ret[$i]["name"] = basename($p["file"]);
                $i++;
            }
        }
        return $ret;
    }
    
    public function pictures_getByID($id)
    {
        $rs = $this->db->prepare('SELECT * FROM '.$this->prfx.'pictures WHERE pid=?');
        $rs->execute(array($id));
        if($rs->rowCount() != 1)
        {
            $this->system->permissions_require("admin.picture.read");
            throw new Exception("Picture Not Found", 404);
        }
        $pict = $rs->fetch(PDO::FETCH_NAMED);
        $user = $this->system->current_user();
        if($user == null || $user["id"] != $pict["uid"])
            $this->system->permissions_require("admin.picture.read");
        return $pict;
    }
    
    public function pictures_getThumb($id, $w, $h)
    {
        $rs = $this->db->prepare('SELECT pid, file, public, uid, login FROM '.$this->prfx.'pictures LEFT JOIN '.$this->prfx.'users USING(uid) WHERE pid=?');
        $rs->execute(array($id));
        if($rs->rowCount() != 1)
        {
            $this->system->permissions_require("admin.picture.read");
            throw new Exception("Picture Not Found", 404);
        }
        $pict = $rs->fetch(PDO::FETCH_NAMED);
        $user = $this->system->current_user();
        if($pict["public"] == 0 && ($user == null || $user["id"] != $pict["uid"]))
            $this->system->permissions_require("admin.picture.read");
        if(!file_exists($this->path.$pict["login"].'/'.$pict["file"]))
            throw new Exception("Picture Not Found Locally", 404);
        
        $orig = imagecreatefromjpeg($this->path.$pict["login"].'/'.$pict["file"]);
        $ow = imagesx($orig);
        $oh = imagesy($orig);
        $ret = imagecreatetruecolor($w, $h);
        $r1 = $w/$h;
        $r2 = $ow/$oh;
        $a = $r1 > $r2 ? $ow : $oh * $w / $h;
        $b = $r1 > $r2 ? $ow * $h / $w : $oh;
        imagecopyresized($ret, $orig, 0, 0, ($ow-$a)/2, ($oh-$b)/2, $w, $h, $a, $b);
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
