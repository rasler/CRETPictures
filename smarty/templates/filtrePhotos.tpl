{* Template pour page d'accueil pour utilisateur connecté *}

{extends file="structure.tpl"}

{block name=title}eBime - Accueil{/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="../index.php">eBime Pictures - A new way of sharing your pics!</a>{/block}

{* ___________________________________________ BLOCK ENCART CONNEXION ___________________________________________ *}

{block name=encartConnexion}
{if $name == ""}
<form method="POST" action="connexion.php?do=login">
    <table>
        <tr>
            <td>Login: </td>
            <td><input type="text" name="nom" maxlength="14" onfocus="this.value=''"/></td>
        </tr><br/>
        <tr>
            <td>Mot de passe: </td>
            <td><input type="password" name="mot_passe"/></td>
        </tr><br/>
        <tr>
            <td><input type="submit" value="Login"/></td>
            <td><a href="#">S'inscrire</a></td>
        </tr>
    </table>
</form>
{else}
    <br/><br/>
    <table>
        <tr>
            Bienvenu(e) {$name|Default:""} sur votre compte!
        </tr><br/><br/>
        <tr>
            <a href="../connexion.php?do=logout">Se déconnecter</a>
        <tr/>
    </table>
{/if}
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

{* ############################################  FORMULAIRE FILTRES  ############################################ *}

    <form method="POST" action="filtrePhotos.php?do=filter">
        <tr>
            <td>Size : </td>
            <td><SELECT name="paramSize">
                <OPTION value="">--</OPTION>
                <OPTION value=">">More than</OPTION>
                <OPTION value="<">Less Than</OPTION>
                <OPTION value="=">Equals To</OPTION>
            </SELECT></td>
            <td><input type="text" name="size"/> octets</td>
        </tr><br/>
        <tr>
            <td>Personnes figurant sur la photo: </td><br/>
            <td><textarea name="listPersonnes" rows="5" cols="50"></textarea></td>
        </tr><br/>
        <tr>
            <td>Date de prise: </td>
            <td>
                <SELECT name="mois">
                    <OPTION value=""></OPTION>
                    <OPTION value="01">Janvier</OPTION>
                    <OPTION value="02">Février</OPTION>
                    <OPTION value="03">Mars</OPTION>
                    <OPTION value="04">Avril</OPTION>
                    <OPTION value="05">Mai</OPTION>
                    <OPTION value="06">Juin</OPTION>
                    <OPTION value="07">Juillet</OPTION>
                    <OPTION value="08">Aout</OPTION>
                    <OPTION value="09">Septembre</OPTION>
                    <OPTION value="10">Octobre</OPTION>
                    <OPTION value="11">Novembre</OPTION>
                    <OPTION value="12">Décembre</OPTION>
                </SELECT>
            </td>
            <td><input type="text" name="annee"/></td>
        </tr>
        <br/><br/>
        <tr><input type="submit" value="Filtrer"/></tr>
    </form>

{* #############################################  AFFICHAGE PHOTOS  ############################################# *}

    <br/><br/>
    <table>
        {foreach from=$tabPics item=photo}
            <td>
                <a href="apercuPhoto.php?img={$photo->pid}">
                    <img src="../app/picture/{$photo->pid}/thumb/255x255"/>
                </a><br/>
                <center>
                {$photo->title}
                <a href="apercuPhoto.php?img={$photo->pid}&do=modify">
                    <img src="../images/modif.gif" width="20px"/>
                </a>
                <a href="../index.php?suppPic={$photo->pid}">
                    <img src="../images/supp.gif" width="20px" 
                        onClick="confirm('Voulez-vous vraiment supprimer cette photo?')"/>
                </a>
                </center>
            </td>
        {/foreach}
    </table>
    {/if}
{/block}