{* Template pour page d'accueil *}

{extends file="structure.tpl"}

{block name=title}eBime - Accueil{/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=lien}<a href="#">eBime Pictures - A new way of sharing your pics!</a>{/block}

{* ___________________________________________ BLOCK ENCART CONNEXION ___________________________________________ *}

{block name=encartConnexion}
{assign var="connexion" value=$connexion|default:"OK"}
{if $connexion eq "failed"}
    <div id="erreur"><br/>Erreur connexion !!</div>
{/if}
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
{/block}

{* _________________________________________________ BLOCK MENU _________________________________________________ *}

{block name=menu}
    <h2><a href="PagesSite/filtrePhotos.php">Filtre photo</a></h2>
{/block}

{* _________________________________________________ BLOCK BODY _________________________________________________ *}

{block name=body}
Bienvenu(e) sur le site eBime: Ce site permet de partager des photos!
{/block}