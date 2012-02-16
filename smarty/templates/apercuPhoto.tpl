{* Template pour page d'aperçu de photo *}

{extends file="structure.tpl"}

{block name=title}eBime - Aperçu Photo{/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="../index.php">eBime Pictures - A new way of sharing your pics!</a>{/block}

{* ___________________________________________ BLOCK ENCART CONNEXION ___________________________________________ *}

{block name=encartConnexion}
    <br/><br/>
    <table>
        <tr>
            Bienvenu {$name|Default:""} sur votre compte!
        </tr><br/><br/>
        <tr>
            <a href="connexion.php?do=logout">Se déconnecter</a>
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
        <li><a href="monProfil.php">Mon profil perso</a></li>
        <li><a href="mesProfils.php">Profils partagés</a></li>
    </ul>

    {if $perms[5] == true}
    <h2>Gestion de photos</h2>
    <ul>
        <li><a href="mesPhotos.php?currentFolder=">Mes photos</a></li>
    </ul>
    {/if}
{/block}

{* _________________________________________________ BLOCK BODY _________________________________________________ *}

{block name=body}
    {if $perms[4] == true}
        <img src="../app/picture/{$imageID}/resize/800x"/>
        <br/><br/>
        <table>
            <tr>
                <td>Titre photo: </td>
                <td>{$image.title}</td>
            </tr>
            <tr>
                <td>Taille fichier:</td>
                <td>{$image.size} octets</td>
            </tr>
            <tr>
                <td>Date de creation:</td>
                <td>{$image.creation}</td>
            </tr>
            <tr>
                <td>Acces:</td>
                <td>
                    {if $image.public == 1}Public
                    {elseif $image.public == 0}Privé
                    {/if}
                </td>
            </tr>
            <tr>
                <td>Personnes figurant sur la photo:</td>
                <td></td>
            </tr>
        </table>

        <br/><br/>
        <table>
            <tr>
                <td><a href="apercuPhoto.php?img={$imageID}&do=modify">
                    <input type="button" value="Modifier les informations"/>
                </a></td>
                <td><a href="../index.php?suppPic={$imageID}">
                    <input type="button" value="Supprimer la photo" 
                        onClick="confirm('Voulez-vous vraiment supprimer cette photo?');"/>
                </a></td>
            </tr>
        </table>
    {/if}
{/block}