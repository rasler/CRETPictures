{* Template pour l'ajout de photos *}

{* On fait hériter du template structure.tpl *}
{extends file="structure.tpl"}

{block name=title}eBime - Ajout de photo{/block}

{block name=img}<img src="../images/logo_cret.png" alt="logo" title="logo" width="125px" />{/block}

{block name=encartConnexion}
<br/><br/>
    <table>
        <tr>
            <span font-size="16px">Bienvenu {$name="Utilisateur1"} sur votre compte!</span>
        </tr><br/><br/>
        <tr>
            <a href="../connexion.php?do=logout">Se déconnecter</a>
        <tr/>
    </table>
{/block}

{* ________________________________________________ BLOCK STYLES ________________________________________________ *}

{block name="styles"}
<style type="text/css">
    .ds_box {
        background-color: #FFF;
        border: 1px solid #000;
        position: absolute;
        z-index: 32767;
    }

    .ds_tbl {    background-color: #FFF; }

    .ds_head {
        background-color: #333;
        color: #FFF;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        font-weight: bold;
        text-align: center;
        letter-spacing: 2px;
    }

    .ds_subhead {
        background-color: #CCC;
        color: #000;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        width: 32px;
    }

    .ds_cell {
        background-color: #EEE;
        color: #000;
        font-size: 13px;
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
        padding: 5px;
        cursor: pointer;
    }

    .ds_cell:hover { background-color: #F3F3F3;  }
</style>
{/block}

{* _______________________________________________ BLOCK SCRIPTJS _______________________________________________ *}

{block name=scriptjs}
    <script type="text/javascript">
        i = 0;
        function addForm(){
            var Obj = document.getElementById('form');
            if(Obj){
                var pname = document.createElement('input');
                pname.type = "file";
                pname.name = "file" + i;
                Obj.appendChild(pname);
                i++;
            }
        }
    </script>
{/block}

{* _________________________________________________ BLOCK MENU _________________________________________________ *}

{block name=menu}
    {if $perms[0] == true || $perms[1] == true || $perms[2] == true || $perms[3] == true}
    <h2>Administration</h2>
    <ul>
        {if $perms[0] == true}<li><a href="#">Ajout user(s)</a></li>{/if}
        {if $perms[1] == true}<li><a href="#">Compte user(s)</a></li>{/if}
        {if $perms[2] == true}<li><a href="#">Mise à jour user(s)</a></li>{/if}
        {if $perms[3] == true}<li><a href="#">Suppression user(s)</a></li>{/if}
    </ul>
    {/if}

    <h2>Gestion de profil</h2>
    <ul>
        <li><a href="#">Mon profil perso</a></li>
        <li><a href="#">Profils partagés</a></li>
    </ul>

    <h2>Gestion de photos</h2>
    <ul>
        <li><a href="PagesSite/mesPhotos.php">Mes photos</a></li>
        {if $perms[7] == true}<li><a href="PagesSite/ajoutPhoto.php">Ajout de photos</a></li>{/if}
    </ul>
{/block}

{* _________________________________________________ BLOCK BODY _________________________________________________ *}

{block name=body}

    <!--C'est dans cette table que va être affichée le calendrier-->
    <table class="ds_box" cellpadding="0" cellspacing="0" id="container" style="display: none;">
        <tr>
            <td id="output_el"></td>
        </tr>
    </table>

{* ############################################  SCRIPT CALENDRIER  ############################################# *}

    <script type="text/javascript">
        //initDate est la date actuelle (on ouvre le calendrier au mois correspondant)
        var initDate = new Date();
        var initMonth = initDate.getMonth() + 1;
        var initYear = initDate.getFullYear();

        function calendarGetElement(id) {	return document.getElementById(id);	}

        // récuperer bords gauche et haut de l'element
        function getLeftBorder(elem) {
            var tmp = elem.offsetLeft;
            elem = elem.offsetParent;
            while(elem) {
                tmp += elem.offsetLeft;
                elem = elem.offsetParent;
            }
            return tmp;
        }

        function getTopBorder(elem) {
            var tmp = elem.offsetTop;
            elem = elem.offsetParent;
            while(elem) {
                tmp += elem.offsetTop;
                elem = elem.offsetParent;
            }
            return tmp;
        }

        var outputElement = calendarGetElement('output_el');
        var containerElement = calendarGetElement('container');
        var outputBuffer = '';

        function cleanOutputBuffer() {  outputBuffer = '';    }

        function flushOutputBuffer() {
            outputElement.innerHTML = outputBuffer;
            cleanOutputBuffer();
        }

        function ds_echo(param) {   outputBuffer += param;  }

        var element;
        var mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 
                    'Octobre', 'Novembre', 'Décembre'];
        var jours = ['Dim', 'Lun', 'Mar', 'Me', 'Jeu', 'Ven', 'Sam'];

        // Calendar template
        function calendarTemplateHeader(date) {
            return '<table cellpadding="3" cellspacing="1" class="ds_tbl">' + 
                '<tr>' +
                    '<td class="ds_head" style="cursor: pointer" onclick="anneePrec();">&lt;&lt;</td>' +
                    '<td class="ds_head" style="cursor: pointer" onclick="moisPrec();">&lt;</td>' +
                    '<td class="ds_head" style="cursor: pointer" onclick="calendarClose();" colspan="3">[Fermer]</td>' +
                    '<td class="ds_head" style="cursor: pointer" onclick="moisSuiv();">&gt;</td>' +
                    '<td class="ds_head" style="cursor: pointer" onclick="anneeSuiv();">&gt;&gt;</td>' +
                '</tr>' +
                '<tr>' +
                    '<td colspan="7" class="ds_head">' + date + '</td>' +
                '</tr>' +
                '<tr>';
        }

        function calendarTemplateDaysRow(nomJours) {    return '<td class="ds_subhead">' + nomJours + '</td>';  }
        function calendarTemplateNewWeek() {    return '</tr><tr>'; }
        function ds_template_blank_cell(colspan) {  return '<td colspan="' + colspan + '"></td>';   }

        function calendarTemplateDay(d, m, y) {
            return '<td class="ds_cell" onclick="dateChosen(' + d + ',' + m + ',' + y + ')">' + d + '</td>';	
        }

        function calendarTemplateFooter() { return '</tr>' + '</table>';    }

        function calendarDraw(m, y) {
            cleanOutputBuffer();

            ds_echo (calendarTemplateHeader(mois[m - 1] + ' ' + y));    //Header
            for (i = 0; i < 7; i ++) {  ds_echo (calendarTemplateDaysRow(jours[i]));    }   //Jours de la semaine

            var newDate = new Date();
            newDate.setMonth(m - 1);
            newDate.setFullYear(y);
            newDate.setDate(1);

            if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) { days = 31;  }
            else if (m == 4 || m == 6 || m == 9 || m == 11) {   days = 30; }
            else {  days = (y % 4 == 0) ? 29 : 28;    }

            var first_day = newDate.getDay();
            var first_loop = 1;

            // Start the first week
            ds_echo (calendarTemplateNewWeek());
            // If sunday is not the first day of the month, make a blank cell...
            if (first_day != 0) {   ds_echo (ds_template_blank_cell(first_day));    }

            var j = first_day;
            for (i = 0; i < days; i ++) {
                // Today is sunday, make a new week.
                // If this sunday is the first day of the month,
                // we've made a new row for you already.
                if (j == 0 && !first_loop) {    ds_echo (calendarTemplateNewWeek());    }

                // Make a row of that day!
                ds_echo (calendarTemplateDay(i + 1, m, y));
                // This is not first loop anymore...
                first_loop = 0;
                // What is the next day?
                j ++;
                j %= 7;
            }

            ds_echo (calendarTemplateFooter());

            flushOutputBuffer();
            containerElement.scrollIntoView();
        }

        function calendarShow(param) {
            element = param;

            // initialisation d'une nouvelle date (heure actuelle) pour afficher le calendrier
            var newDate = new Date();
            newDateMonth = newDate.getMonth() + 1;
            newDateYear = newDate.getFullYear();

            // affichage du calendrier
            calendarDraw(newDateMonth, newDateYear);
            containerElement.style.display = '';

            // On bouge le container du calendrier à l'endroit où se trouve l'input date
            bordGauche = getLeftBorder(param);
            bordHaut = getTopBorder(param) + param.offsetHeight;
            containerElement.style.left = bordGauche + 'px';
            containerElement.style.top = bordHaut + 'px';

            containerElement.scrollIntoView();
        }

        function calendarClose() {  containerElement.style.display = 'none'; }

        // Affichage du mois suivant
        function moisSuiv() {
            initMonth ++;
            // Si Décembre --> incrémenter l'année
            if (initMonth > 12) {
                initMonth = 1; 
                initYear++;
            }
            calendarDraw(initMonth, initYear);
        }

        // Affichage du mois précédent
        function moisPrec() {
            initMonth = initMonth - 1;
            // Si Janvier --> décrémenter l'année
            if (initMonth < 1) {
                initMonth = 12; 
                initYear = initYear - 1;
            }
            calendarDraw(initMonth, initYear);
        }

        // Affichage de l'année suivante
        function anneeSuiv() {
            initYear++;
            calendarDraw(initMonth, initYear);
        }

        // Affichage de l'année précédente
        function anneePrec() {
            initYear = initYear - 1;
            calendarDraw(initMonth, initYear);
        }

        // Formatage de la date retournée par le calendrier
        function formaterDate(d, m, y) {
            m2 = '00' + m;
            m2 = m2.substr(m2.length - 2);
            d2 = '00' + d;
            d2 = d2.substr(d2.length - 2);
            return d2 + '/' + m2 + '/' + y;
        }

        // When the user clicks the day.
        function dateChosen(d, m, y) {
            calendarClose();

            if (typeof(element.value) != 'undefined') {	element.value = formaterDate(d, m, y);	}
            else if (typeof(element.innerHTML) != 'undefined') {    element.innerHTML = formaterDate(d, m, y);	}
            else {	alert (formaterDate(d, m, y));	}
        }
    </script>

    <script>
        function validerForm(formulaire){
            if(document.forms["formulaire"]["photoFile"].value == ""){
                alert("Veuillez choisir un fichier!");
                return false;
            }
            return true;    // formulaire valide
        }
    </script>

{* ################################################  FORMULAIRE  ################################################ *}

    <form method="POST" name="formulaire" enctype="multipart/form-data" 
        action="../PagesSite/ajoutPhoto.php?do=ajout" onsubmit="return validerForm(this)">
        <div class="infosSaisies">
            <input type="file" name="photoFile" value=""/><br/>
            
            <span color="#fff">Titre de la photo (facultatif): </span>
            <input type="text" name="titlePic"/><br/>
            
            Date de la prise de la photo : 
            <input onclick="calendarShow(this);" name="date" readonly="readonly" style="cursor: text" /><br />
            
            Personnes apparaissant sur la photo : <br/>
            <textarea name="listPersonnes" rows="5" cols="50"></textarea><br/>

            <input type="submit" value="Valider" />
        </div>
    </form>

{/block}