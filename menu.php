<?php
    $sys = new System();
     
    if(permissions_test("admin.permission.grant"))
    {
        echo '<a href="gestion_droit_admin.php">Ajouter des permissions</a>';
    }
    if(permissions_test("admin.permission.revoke"))
    {
        echo '<a href="gestion_droit_admin.php">Supprimer des permissions</a>';
    }
    if(permissions_test("admin.picture.read"))
    {
        echo '<a href="gestion_droit_admin.php">Visionner les photos des autres membres</a>';
    }
    if(permissions_test("admin.picture.create"))
    {
        echo '<a href="gestion_droit_admin.php">Ajout utilisateurs</a>';
    }
    if(permissions_test("admin.user.read"))
    {
        echo '<a href="gestion_droit_admin.php">Lecture des informations desutilisateurs</a>';
    }
    if(permissions_test("admin.user.update"))
    {
        echo '<a href="gestion_droit_admin.php">Mise à jour des utilisateurs</a>';
    }
    if(permissions_test("admin.user.delete"))
    {
        echo '<a href="gestion_droit_admin.php">Supprimer des utilisateurs</a>';
    }
    echo '<a href="voirPhoto.php">Voir mes photos</a>';
    echo '<a href="triePhoto.php">Trier mes photographies</a>';
    if(permissions_test("application.picture.upload"))
    {
        echo '<a href="ajoutPhoto.php.php">Ajouter des photographies</a>';
    }    
?>