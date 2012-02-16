{* Template pour page de visualisation de ses profils *}

{extends file="structure.tpl"}

{block name=title}eBime - Mes Profils{/block}

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
        <li><a href="monProfil.php">Mon profil perso</a></li>
        <li><a href="mesProfils.php">Profils partagés</a></li>
    </ul>

    <h2>Gestion de photos</h2>
    <ul>
        <li><a href="mesPhotos.php?currentFolder=">Mes photos</a></li>
    </ul>
{/block}

{* _________________________________________________ BLOCK BODY _________________________________________________ *}

{block name=body}
    <a href="creerProfil.php"><input type="button" value="Créer nouveau profil"/></a>

    <br/><br/>
{if $profils == NULL}
    Aucun profil enregistré
{else}
    <table>
        {section name=content loop=$profils}
            {if $profils[content].nickName != ""}
<tr>
                <td>
                    <a href="apercuProfil.php?profil={$profils[content].prid}">{$profils[content].nickName}</a>
                </td>
                <td><a href="mesProfils.php?suppProfil={$profils[content].prid}">
                    <img src="../images/supp.gif" width="20px"
                        onClick="confirm('Voulez-vous vraiment supprimer ce profil?')"/>
                </a></td>
</tr>
            {elseif $profils[content].firstName != ""}
<tr>
                <td>
                    <a href="apercuProfil.php?profil={$profils[content].prid}">{$profils[content].firstName}</a>
                </td>
                <td><a href="mesProfils.php?suppProfil={$profils[content].prid}">
                    <img src="../images/supp.gif" width="20px"
                        onClick="confirm('Voulez-vous vraiment supprimer ce profil?')"/>
                </a></td>
</tr>
            {/if}
        {/section}
    </table>
{/if}
{/block}