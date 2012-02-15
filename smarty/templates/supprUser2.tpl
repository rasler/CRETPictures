{* Template pour page d'ajout d'user *}

{extends file="structure.tpl"}

{block name=title}eBime - Ajout User(s){/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="../index.php">eBime Pictures - A new way of sharing your pics!</a>{/block}

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
        {if $perms[2] == true}<li><a href="UserUpdate.php">Mise à jour user(s)</a></li>{/if}
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
<span color="#fff">Utilisateur {$Login} supprimé</span></br></br></br>

<a href="UserUpdate.php">Revenir au menu de gestion des utilisateurs </a><br/><br/></br>
            

    

{/block}