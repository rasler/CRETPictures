{* Template pour page d'accueil *}

{extends file="structure.tpl"}

{block name=title}eBime - Accueil{/block}
{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=encartConnexion}
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
        <tr/>
    </table>
</form>
{/block}

{block name=body}
Présentation du site !!
{/block}