{* Template pour page d'accueil pour utilisateur connecté *}

{extends file="structure.tpl"}

{block name=title}eBime - Accueil{/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="#">eBime Pictures - A new way of sharing your pics!</a>{/block}

{* ___________________________________________ BLOCK ENCART CONNEXION ___________________________________________ *}

{block name=encartConnexion}
    <br/><br/>
    <table>
        <tr>
            Bienvenu(e) {$name|Default:""} sur votre compte!
        </tr><br/><br/>
        <tr>
            <a href="connexion.php?do=logout">Se déconnecter</a>
        <tr/>
    </table>
{/block}

{* _________________________________________________ BLOCK MENU _________________________________________________ *}

{block name=menu}
    <h2><a href="PagesSite/filtrePhotos.php">Filtre photo</a></h2>

    {if $perms[1] == true && ($perms[0] == true || $perms[2] == true || $perms[3] == true)}
    <h2>Administration</h2>
    <ul>
        {if $perms[0] == true}<li><a href="PagesSite/ajoutUser.php">Ajout user(s)</a></li>{/if}
        {if $perms[2] == true}<li><a href="PagesSite/UserUpdate.php">Mise à jour user(s)</a></li>{/if}
    </ul>
    {/if}

    <h2>Gestion de profil</h2>
    <ul>
        <li><a href="PagesSite/monProfil.php">Mon profil perso</a></li>
        <li><a href="PagesSite/mesProfils.php">Profils partagés</a></li>
    </ul>

    {if $perms[5] == true}
    <h2>Gestion de photos</h2>
    <ul>
        <li><a href="PagesSite/mesPhotos.php?currentFolder=">Mes photos</a></li>
    </ul>
    {/if}
{/block}

{* _________________________________________________ BLOCK BODY _________________________________________________ *}

{block name=body}
    {if $perms[4] == true}
    <table>
        {foreach from=$tabPics item=photo}
            <td>
                <a href="apercuPhoto.php?img={$photo->pid}">
                    <img src="app/picture/{$photo->pid}/thumb/255x255"/>
                </a><br/>
                <center>
                {$photo->title}
                <a href="apercuPhoto.php?img={$photo->pid}&do=modify">
                    <img src="images/modif.gif" width="20px"/>
                </a>
                <a href="index.php?suppPic={$photo->pid}">
                    <img src="images/supp.gif" width="20px" 
                        onClick="confirm('Voulez-vous vraiment supprimer cette photo?')"/>
                </a>
                </center>
            </td>
        {/foreach}
    </table>
    {/if}
{/block}