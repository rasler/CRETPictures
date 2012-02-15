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
        $user = $this->system->current_user();
        if($user == null)
            return new Exception ("Unkown User");
        $this->system->permissions_require("application.picture.upload");
        mkdir($this->path.$this->usr_login."/".$fullName);
    }
    
    public function folders_remove($fullName)
    {
        $user = $this->system->current_user();
        if($user == null)
            return new Exception ("Unkown User");
        if(is_dir($this->path.$this->usr_login."/".$fullName))
        {
            $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'pictures WHERE uid=? AND file LIKE ?');
            $rs->execute(array($user['id'], $fullName."/%"));
            $this->recursiveRemove($this->path.$this->usr_login."/".$fullName);
        }
        else
            throw new Exception("Folder Not Found", 404);
    }
    private function recursiveRemove($path)
    {
        if($dossier = opendir($path))
        {
            while(false !== ($fichier = readdir($dossier)))
            {
                if(is_dir($path.'/'.$fichier)&&$fichier!="."&&$fichier!="..")
                    $this->recursiveRemove($path.'/'.$fichier);
                elseif(is_file($path.'/'.$fichier))
                    unlink($path.'/'.$fichier);
            }
            closedir($dossier);
            rmdir($path);
        }
    }
    
    public function folders_rename($fullName, $newName)
    {
        $user = $this->system->current_user();
        if($user == null)
            return new Exception ("Unkown User");
        $this->system->permissions_require("application.picture.upload");
        if(is_dir($this->path.$this->usr_login."/".$fullName)&&is_dir(dirname($this->path.$this->usr_login."/".$newName))&&dirname($this->path.$this->usr_login."/".$newName)!=$this->path.$this->usr_login."/".$fullName&&!is_dir($this->path.$this->usr_login."/".$newName))
        {
            $this->db->beginTransaction();
            $rs = $this->db->prepare('SELECT pid, file FROM '.$this->prfx.'pictures WHERE file LIKE ?');
            $rs->execute(array($fullName."/%"));
            $rs2 = $this->db->prepare('UPDATE '.$this->prfx.'pictures SET file=? WHERE pid = ?');
            while($pic = $rs->fetch(PDO::FETCH_NAMED))
            {
                $rs2->execute(array(substr_replace($pic["file"], $newName, 0, strlen($fullName)), $pic["pid"]));
            }
            if(!$this->db->commit())
                throw new Exception("Erreur de modification en base de données");
            rename($this->path.$this->usr_login."/".$fullName, $this->path.$this->usr_login."/".$newName);
        }
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
    
    public function pictures_update($picture)
    {
        if(!isset($picture["pid"]))
            throw new Exception("Picture Not Found", 404);
        $origin = $this->pictures_getByID($picture["pid"]);
        if($origin["owner"] != $this->usr_login)
            throw new Exception ("You cannot update a picture which does not belong to you");
        $this->db->beginTransaction();
        
        // mise à jour du flag public
        if(isset($picture["public"]))
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'pictures SET public=? WHERE pid=?');
            $rs->execute(array($picture["public"], $origin["pid"]));
        }
        
        // mise à jour du titre
        if(isset($picture["title"]))
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'pictures SET title=? WHERE pid=?');
            $rs->execute(array($picture["title"], $origin["pid"]));
        }
        
        // mise à jour de la date de création
        if(isset($picture["creation"]))
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'pictures SET creation=? WHERE pid=?');
            $rs->execute(array($picture["creation"], $origin["pid"]));
        }
        
        // mise à jour du chemin
        if(isset($picture["file"]))
        {
            $rs = $this->db->prepare('UPDATE '.$this->prfx.'pictures SET file=? WHERE pid=?');
            $rs->execute(array($picture["file"], $origin["pid"]));
            
            if($this->db->commit())
            {
                if(!is_dir(dirname($this->path.$this->usr_login."/".$picture["file"])))
                    mkdir(dirname($this->path.$this->usr_login."/".$picture["file"]));
                rename($this->path.$this->usr_login."/".$origin["file"], $this->path.$this->usr_login."/".$picture["file"]);
            }
        }
            
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
        $rs = $this->db->prepare('SELECT p.*, login as owner FROM '.$this->prfx.'pictures p LEFT JOIN '.$this->prfx.'users u USING(uid) WHERE pid=?');
        $rs->execute(array($id));
        if($rs->rowCount() != 1)
        {
            $this->system->permissions_require("admin.picture.read");
            throw new Exception("Picture Not Found", 404);
        }
        $pict = $rs->fetch(PDO::FETCH_NAMED);
        $user = $this->system->current_user();
        if(($user == null || $user["id"] != $pict["uid"]) && $pict["public"] == 0)
            $this->system->permissions_require("admin.picture.read");
        return $pict;
    }
    
    public function pictures_getThumb($id, $w, $h)
    {
        $pic = $this->pictures_getByID($id);
        if(!file_exists($this->path.$pic["owner"].'/'.$pic["file"]))
            throw new Exception("Picture Not Found Locally", 404);
        
        $orig = imagecreatefromjpeg($this->path.$pic["owner"].'/'.$pic["file"]);
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
        
    public function pictures_readFile($id)
    {
        $pic = $this->pictures_getByID($id);
        if(!file_exists($this->path.$pic["owner"].'/'.$pic["file"]))
            throw new Exception("Picture Not Found Locally", 404);
        
        readfile($this->path.$pic["owner"].'/'.$pic["file"]);
    }
    
    public function pictures_resize($id, $w, $h)
    {
        $pic = $this->pictures_getByID($id);
        if(!file_exists($this->path.$pic["owner"].'/'.$pic["file"]))
            throw new Exception("Picture Not Found Locally", 404);
        
        $orig = imagecreatefromjpeg($this->path.$pic["owner"].'/'.$pic["file"]);
        $ow = imagesx($orig);
        $oh = imagesy($orig);
        if($w == null && $h == null)
        {
            return $orig;
        }
        if($w != null && $h != null)
        {
            if($w/$ow < $h/$oh)
                $h=null;
            else
                $w = null;
        }
        if($w != null && $h == null)
        {
            $ret = imagecreatetruecolor($w, $oh*($w/$ow));
            imagecopyresized($ret, $orig, 0, 0, 0, 0, $w, $oh*($w/$ow), $ow, $oh);
            return $ret;
        }
        if($w == null && $h != null)
        {
            $ret = imagecreatetruecolor($ow*($h/$oh), $h);
            imagecopyresized($ret, $orig, 0, 0, 0, 0, $ow*($h/$oh), $h, $ow, $oh);
            return $ret;
        }
    }
    
    public function pictures_remove($pid)
    {
        $this->system->permissions_ignore();
        try
        {
            $pic = $this->pictures_getByID($pid);
        }
        catch(Exception $e)
        {
            $this->system->permissions_ignore(false);
            $this->system->permissions_require("admin.picture.delete");
            throw $e;
        }
        $this->system->permissions_ignore(false);
        $user = $this->system->current_user();
        
        if($user == null || $user["id"] != $pic["uid"])
            $this->system->permissions_require("admin.picture.delete");
        
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'pictures WHERE pid=?');
        $rs->execute(array($pid));
        if(file_exists($this->path.$pic["owner"].'/'.$pic["file"]))
            unlink($this->path.$pic["owner"].'/'.$pic["file"]);
    }
        
    public function pictures_share($pid, $prid)
    {
        $pi = $this->pictures_getByID($pid);
        $user = $this->system->current_user();
        
        if($pi["owner"] != $user["id"])
            throw new Exception("Only the owner of the picture can share it");
        
        $rs = $this->db->prepare('INSERT INTO '.$this->prfx.'shares VALUES (?, ?)');
        $rs->execute(array($pid, $prid));
    }
    
    public function pictures_unshare($pid, $prid)
    {
        $pi = $this->pictures_getByID($pid);
        $user = $this->system->current_user();
        
        if($pi["owner"] != $user["id"])
            throw new Exception("Only the owner of the picture can unshare it");
        
        $rs = $this->db->prepare('DELETE FROM '.$this->prfx.'shares WHERE pid =? AND prid = ?)');
        $rs->execute(array($pid, $prid));   
    }
    
    // returns array of profiles
    public function pictures_sharedWidth($pid)
    {
        $pi = $this->pictures_getByID($pid);
        $user = $this->system->current_user();
        
        if($pi["owner"] != $user["id"])
            throw new Exception("Only the owner of the picture can see shares");
        
        $rs = $this->db->prepare('SELECT p.* FROM '.$this->prfx.'profiles p JOIN '.$this->prfx.'shares s USING(prid) WHERE pid =?');
        $rs->execute(array($pid));
        return $rs->fetchAll(PDO::FETCH_NAMED);
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
