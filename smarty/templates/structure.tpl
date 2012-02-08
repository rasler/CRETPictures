{* Template de la structure générale des pages du site *}

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <title>{block name=title}{/block}</title>
        <link rel="stylesheet" type="text/css" href="../CSSFiles/structure.css"/>
        
        {*Pour importation de fichiers css supplémentaires*}
        {block name=styles}{/block}
        {block name=scriptjs}{/block}
    </head>

    <body>
        <div id="global">
            <div id="header">
                {block name=img}{/block}
                <h1><a href="#">eBime Pictures - A new way of sharing your pics!</h1>
                <div id="encartConnexion">
                    {block name=encartConnexion}{/block}
                </div>
            </div>

            <div id="barre_menu">  
                <h2>Tri des photos</h2>
                <ul>
                    <li><a href="#">par lieu</li>
                    <li><a href="#">par personne</li>
                    <li><a href="#">par date</li>
                    <li><a href="#">par date2</li>
                    <li><a href="#">par taille</li>
                </ul>
                {block name=menu}{/block}
                
            </div>

            <div id="corps">{block name=body}{/block}</div>
        </div>
    </body>
</html>