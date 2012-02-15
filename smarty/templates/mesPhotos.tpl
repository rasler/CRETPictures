{* Template pour page de visualisation de ses photos & dossiers *}

{extends file="structure.tpl"}

{block name=title}eBime - Mes Photos{/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="../index.php">eBime Pictures - A new way of sharing your pics!</a>{/block}

{* _______________________________________________ BLOCK SCRIPTJS _______________________________________________ *}

{block name=scriptjs}
<script type="text/javascript">
    function nouveauDossier(currentFolder){
        var saisie = prompt("Nom du nouveau dossier", "");
        if(saisie){
            window.location.replace("mesPhotos.php?saisie="+saisie+"&currentFolder="+currentFolder);
        }
    }

    function modifDossier(currentName, currentFolder){
        var saisie = prompt("Nom du dossier", currentName);
        if(saisie){
            window.location.replace("mesPhotos.php?current="+currentName+"&change="+saisie+"&currentFolder="+currentFolder);
        }
    }
</script>
{/block}

{* ___________________________________________ BLOCK ENCART CONNEXION ___________________________________________ *}

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

{* _________________________________________________ BLOCK MENU _________________________________________________ *}

{block name=menu}
    <h2><a href="filtrePhotos.php">Filtre photo</a></h2>

    {if $perms[0] == true || $perms[1] == true || $perms[2] == true || $perms[3] == true}
    <h2>Administration</h2>
    <ul>
        {if $perms[0] == true}<li><a href="ajoutUser.php">Ajout user(s)</a></li>{/if}
        {if $perms[2] == true}<li><a href="updateuser.php">Mise à jour user(s)</a></li>{/if}
    </ul>
    {/if}

    <h2>Gestion de profil</h2>
    <ul>
        <li><a href="#">Mon profil perso</a></li>
        <li><a href="#">Profils partagés</a></li>
    </ul>

    <h2>Gestion de photos</h2>
    <ul>
        <li><a href="mesPhotos.php?currentFolder=">Mes photos</a></li>
    </ul>
{/block}

{* _________________________________________________ BLOCK BODY _________________________________________________ *}

{block name=body}
    <table>
        <td>
            <input type="button" value="Nouveau dossier" name="nameFolder" onClick="nouveauDossier('{$currentFolder}');"/>
        </td>
        {if $perms[5] == true}
            <td><a href="ajoutPhoto.php?currentFolder={$currentFolder}">
                <input type="button" value="Ajouter photos"/>
            </a></td>
        {/if}
    </table>

    <br/><br/>
    <table>
        {section name=content loop=$tabPhotos}
            {if $tabPhotos[content].type == "folder"}
                <td>
                    <a href="mesPhotos.php?currentFolder={$currentFolder}/{$tabPhotos[content].name}">
                        <img src="../images/folder.png" width="200"/>
                    </a><br/>
                    <center>
                        {$tabPhotos[content].name}
                        <img src="../images/modif.gif" width="20px" onclick="modifDossier('{$tabPhotos[content].name}','{$currentFolder}');"/>
                        <a href="mesPhotos.php?suppFolder={$currentFolder}/{$tabPhotos[content].name}&currentFolder={$currentFolder}">
                            <img src="../images/supp.gif" width="20px" 
                                onClick="confirm('Voulez-vous vraiment supprimer le dossier entier?')"/>
                        </a>
                    </center>
                </td>
            {elseif $tabPhotos[content].type == "picture"}
                <td>
                    <a href="apercuPhoto.php?img={$tabPics[content].id}">
                        <img src="../app/picture/{$tabPics[content].id}/thumb/200x200"/>
                    </a><br/>
                    <center>
                        {$tabPics[content].title}
                        <a href="apercuPhoto.php?img={$tabPics[content].id}$do=modify">
                            <img src="../images/modif.gif" width="20px"/>
                        </a>
                        <a href="mesPhotos.php?suppPic={$tabPics[content].id}&currentFolder={$currentFolder}">
                            <img src="../images/supp.gif" width="20px" 
                                onClick="confirm('Voulez-vous vraiment supprimer cette photo?')"/>
                        </a>
                    </center>
                </td>
            {/if}
        {/section}
    </table>
{/block}