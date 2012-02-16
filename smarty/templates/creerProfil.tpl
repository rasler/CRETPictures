{* Template pour page d'accueil pour utilisateur connecté *}

{extends file="structure.tpl"}

{block name=title}eBime - Creation Profil{/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="../index.php">eBime Pictures - A new way of sharing your pics!</a>{/block}

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
        {if $perms[0] == true}<li><a href="ajoutUser.php">Ajout user(s)</a></li>{/if}
        {if $perms[2] == true}<li><a href="UserUpdate.php">Mise à jour user(s)</a></li>{/if}
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
    {if $who == "self"}
        Création de son profil perso
        <form method="POST" action="creerProfil.php?do=create&link=user">
    {else}
        Création d'un nouveau profil
        <form method="POST" action="creerProfil.php?do=create">
    {/if}
    <br/><br/>
    <table>
        <tr>
            <td>Nom : </td>
            <td><input type="text" name="lastname" value="{$profil.lastName|Default:""}"/></td>
        </tr>
        <tr>
            <td>Prénom : </td>
            <td><input type="text" name="firstname" value="{$profil.firstName|Default:""}"/></td>
        </tr>
        <tr>
            <td>Date de naissance : </td>
            <td><input type="text" name="birth" value="{$profil.birth|Default:""}"/></td>
        </tr>
        <tr>
            <td>Sexe : </td>
            <td><input type="radio" name="gender" value="male">Homme</input></td>
            <td><input type="radio" name="gender" value="female">Femme</input></td>
        </tr>
        <tr>
            <td>Surnom : </td>
            <td><input type="text" name="nickname" value="{$profil.nickName|Default:""}"/></td>
        </tr>
        <tr>
            <td>Email : </td>
            <td><input type="text" name="email" value="{$profil.email|Default:""}"/></td>
        </tr>
        <tr>
            <td>Num téléphone : </td>
            <td><input type="text" name="phone" value="{$profil.phone|Default:""}"/></td>
        </tr>
    </table>
    <br/><br/>
    <center><input type="submit" value="Enregistrer les modifications"/></center>
    </form>
{/block}