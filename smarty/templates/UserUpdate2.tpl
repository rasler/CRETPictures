{* Template pour page d'ajout d'user *}

{extends file="structure.tpl"}

{block name=title}eBime - Ajout User(s){/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="../index.php">eBime Pictures - A new way of sharing your pics!</a>{/block}

{* ___________________________________________ BLOCK ENCART CONNEXION ___________________________________________ *}

{block name=encartConnexion}
    <br/><br/>
    <table>
        <tr>
            Bienvenu {$name} sur votre compte!
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
        {if $perms[2] == true}<li><a href="UserUpdate.php">Mise à jour user(s)</a></li>{/if}
    </ul>
    {/if}

    <h2>Gestion de profil</h2>
    <ul>
        <li><a href="#">Mon profil perso</a></li>
        <li><a href="#">Profils partagés</a></li>
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
            
    <span color="#fff">Liste des utilisateurs existants : </span></br>

    {foreach key=K item=ind from=$users}
        <tr>
            <td><a href="UserUpdate2.php?Login={$ind.login}">{$ind.login}  </a></td>
            {if $perms[3] == true} <td><a href="supprUser.php?Login={$ind.login}"><img src="../images/supp.gif" alt="logo" title="logo" width="12px" /></a></td>{/if}</br>
        </tr>
    {/foreach}
    </br> </br> 
            

<form method="POST" name="formulaire" enctype="multipart/form-data" 
        action="UserUpdate3.php?do=ajout" onsubmit="return validerForm(this)">
        <div class="infosCreaUser">
            
            <span color="#fff">Modification d'un utilisateur existant </span></br></br>
            
            <span color="#fff">Login de l'utilisateur à modifier : {$Login} </span></br></br>
            <span color="#fff">Information de cet utilisateur : </span></br>

            <tr>ID : {$user.id}</br></tr>
            <tr>Login : {$user.login}</br></tr>
            <tr>Date de création : {$user.creation}</br></tr>
            <tr>Dernière connection : {$user.lastConnection}</br></tr></br>

            {foreach key=K item=ind from=$user.permissions}
                <tr>{$ind}</br></tr>
            {/foreach}
            
            </br> </br>
            
            {if $perms[2] == true}
            <span color="#fff">Séléctionner les droits que vous voulez ajouter, tous les autres seront supprimés: </span></br></br>
            
            <span color="#fff">Ajouter des droits d'administrateur : </span></br>
            
            <input type="hidden" name="Login2" value={$user.login} />
            
            <input type="checkbox" name="AdminGrant" id="AdminGrant" /> <label for="AdminGrant">Autoriser l'ajout de permissions à d'autres utilisateurs</label></br>
            <input type="checkbox" name="AdminRevoke" id="AdminRevoke" /> <label for="AdminRevoke">Autoriser la suppression de permissions à d'autres utilisateurs</label></br>
            
            <input type="checkbox" name="pictureRead" id="pictureRead" /> <label for="PictureRead">Autoriser la lecture d'image dont cet utilisateur n'est pas propriétaire</label></br>
            
            <input type="checkbox" name="UserCreate" id="UserCreate" /> <label for="userCreate">Autoriser la création de nouveaux utilisateurs</label></br>
            <input type="checkbox" name="UserRead" id="UserRead" /> <label for="UserRead">Autoriser la lecture des autres utilisateurs</label></br>
            <input type="checkbox" name="UserUpdate" id="UserUpdate" /> <label for="UserUpdate">Autoriser la mise à jour des autres utilisateurs</label></br>
            <input type="checkbox" name="UserDelete" id="UserDelete" /> <label for="UserDelete">Autoriser la suppression d'utilisateurs</label></br>
             
            </br><span color="#fff">Ajouter des droits d'utilisateur : </span></br>
            <input type="checkbox" name="ApplicationLogin" id="ApplicationLogin" /> <label for="ApplicationLogin">Autoriser la connexion au système</label></br>
            <input type="checkbox" name="PictureUpload" id="PictureUpload" /> <label for="PictureUpload">Autoriser la gestion des albums et ajout de nouvelles images</label></br>
            
            </br><input type="submit" value="Valider" />
        </div>
        {/if}
    </form>

{/block}