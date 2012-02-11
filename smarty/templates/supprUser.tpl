{* Template pour page d'ajout d'user *}

{extends file="structure.tpl"}

{block name=title}eBime - Ajout User(s){/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=encartConnexion}
    <br/><br/>
    <table>
        <tr>
            Bienvenu {$name|Default:""} sur votre compte!
        </tr><br/><br/>
        <tr>
            <a href="../connexion.php?do=logout">Se déconnecter</a>
        <tr/>
    </table>
{/block}

{block name=menu}
    {if $perms[0] == true || $perms[1] == true || $perms[2] == true || $perms[3] == true}
    <h2>Administration</h2>
    <ul>
        {if $perms[0] == true}<li><a href="ajoutUser.php">Ajout user(s)</a></li>{/if}
        {if $perms[1] == true}<li><a href="LectureUser.php">Comptes user(s)</a></li>{/if}
        {if $perms[2] == true}<li><a href="updateUser.php">Mise à jour user(s)</a></li>{/if}
        {if $perms[3] == true}<li><a href="supprUser.php">Suppression user(s)</a></li>{/if}
    </ul>
    {/if}

    <h2>Gestion de profil</h2>
    <ul>
        <li><a href="#">Mon profil perso</a></li>
        <li><a href="#">Profils partagés</a></li>
    </ul>

    <h2>Gestion de photos</h2>
    <ul>
        <li><a href="PagesSite/mesPhotos.php">Mes photos</a></li>
        {if $perms[7] == true}<li><a href="ajoutPhoto.php">Ajout de photos</a></li>{/if}
    </ul>
{/block}

{block name=body}
<form method="POST" name="formulaire" enctype="multipart/form-data" 
        action="../PagesSite/supprUser.php?do=ajout" onsubmit="return validerForm(this)">
        <div class="infosCreaUser">
            
            <span color="#fff">Suppression d'un nouvel utilisateur</span></br></br>
            
            <span color="#fff">Login de l'utilisateur à supprimer : </span>
            <input type="text" name="Login" value=""/><br/>
            
            </br><input type="submit" value="Valider" />
        </div>
    </form>
{/block}