{* Template pour page d'accueil pour utilisateur connecté *}

{extends file="structure.tpl"}

{block name=title}eBime - Accueil{/block}

{block name=styles}<link rel="stylesheet" type="text/css" href="CSSFiles/structure.css"/>{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=encartConnexion}
    <br/><br/>
    <table>
        <tr>
            Bienvenu {$name|Default:""} sur votre compte!
        </tr><br/><br/>
        <tr>
            <a href="/connexion.php?do=logout">Se déconnecter</a>
        <tr/>
    </table>
{/block}

{block name=menu}
    <h2><a href="#">Mon profil</a></h2>
    <h2><a href="../PagesSite/mesPhotos.php">Mes photos</a></h2>
    <h2><a href="../PagesSite/ajoutPhoto.php">Ajouter photos</a></h2>
{/block}