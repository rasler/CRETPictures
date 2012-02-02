<?php include("structure.php"); ?>

<span class ="errorcon">  
    <form method="post" enctype="multipart/form-data" action="conex.php">
        <fieldset>
            <legend>Erreur de Connexion</legend> <!-- Titre du fieldset -->

            <label for="nom">Login ...... :  </label>
            <input type="text" name="nom" id="nom" tabindex="10" /><br />

            <label for="mot_passe">Mot de passe :  </label>
            <input type="password" name="mot_passe" id="mot_passe" tabindex="30" /><br />

            <input type="submit" tabindex="40"><input type="reset" tabindex="50">
        </fieldset>	
    </form>
</span>  