<?php session_start();
/*
 * Copyright (C) 2015 - 2020 Petr Budinský
 * Soubor schoolCanteenAdmin.php je určený pro administraci webové prezentace schoolCanteen.php
 * */
setlocale(LC_TIME, 'cs_CZ.UTF-8');
require '../inc/db.php';
require '../inc/userRequired.php';
if ((empty($currentUser)) || (($currentUser['ADMIN'] == '0') && ($currentUser['JIDELNA'] == '0'))) {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    header('Location: ../logout.php');
    exit();
}
$pageTitle = "Školní jídelna - ";
?>
<!DOCTYPE html>
<html lang="cs">
<?php include "../inc/headAdmin.php"; ?>
<body onload="show('','LISTEK');insertIntoForm('3','MENULISTEK')"><!--scriptSelectAJAX.js-->
<?php include '../inc/logCMS.php' ?>
<header class="admin">
    <?php include "../inc/headerAdmin.php" ?>
</header>
<main class="skolniJidelna adminPart">
    <h3>Editace platnosti jídelního lístku</h3>
    <form id="formMENULISTEK" method="post" enctype="multipart/form-data">
        <input type="hidden" value="MENULISTEK" name="type">
        <input type="date" class="form-control" id="menuDatum" name="menuDatum" aria-label="Začátek platnosti"><br>
        <input type="button" id="updateMENULISTEK" class="btn btn-warning" value='Aktualizovat'>
        <!--scriptUpdateAJAX.js-->
    </form>
    <span id="message-MENULISTEK"></span>
    <h3>Editace jídelního lístku</h3>
    <form id="formLISTEK" method="post" enctype="multipart/form-data">
        <input type="hidden" value="LISTEK" name="type">
        <input type="hidden" id="ID" name="ID" aria-label="ID dne">
        <div class="menuItemsCanteen">
            <div>
                <label for="polevka">Polévka:</label>
                <textarea class="form-control" id="polevka" name="polevka" aria-label="Polévka"></textarea>
            </div>
            <div>
                <label for="hlavniPokrm">Hlavní pokrm:</label>
                <textarea class="form-control" id="hlavniPokrm" name="hlavniPokrm" aria-label="Hlavní pokrm"></textarea>
            </div>
            <div>
                <label for="priloha">Ovoce a zelenina:</label>
                <textarea class="form-control" id="priloha" name="priloha" aria-label="Ovoce a zelenina"></textarea>
            </div>
            <div>
                <label for="napoje">Nápoje:</label>
                <textarea class="form-control" id="napoje" name="napoje" aria-label="Nápoje"></textarea>
            </div>
        </div>
        <input style="display: none" type="button" id="updateLISTEK" class="btn btn-warning" value='Aktualizovat'>
        <!--scriptUpdateAJAX.js-->
        <input type="button" class="btn btn-light" onclick="$('#formLISTEK')[0].reset();" value='Zrušit'>
    </form>
    <span id="message-LISTEK"></span>
    <h4>
        Jídelní lístek
    </h4>
    <a href="../schoolCanteenMenu.php" target="_blank">Aktuální jídelní lístek k tisku</a>
    <div style="overflow-x: auto">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Den</th>
                <th>Polévka</th>
                <th>Hlavní jídlo</th>
                <th>Ovoce a zelenina</th>
                <th>Nápoje</th>
                <th>Upravit</th>
            </tr>
            </thead>
            <tbody id="load-LISTEK"><!--scriptSelectAJAX.js-->
            </tbody>
        </table>
    </div>
    <h3>1. Výdej obědů</h3>
    <table>
        <thead>
        <tr>
            <td>Výdej komu:</td>
            <td>Časové rozmezí:</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Obědy pro cizí strávníky, tzv. "přes ulici"</td>
            <td>11:00 - 11:30 hod.</td>
        </tr>
        <tr>
            <td>Obědy pro MŠ</td>
            <td>11:30 - 11:50 hod.</td>
        </tr>
        <tr>
            <td>Obědy pro ZŠ</td>
            <td>11:50 - 12:40 hod.</td>
        </tr>
        <tr>
            <td>Odpolední svačina v MŠ</td>
            <td>14:15 - 14:30 hod.</td>
        </tr>
        </tbody>
    </table>
    <h3>2. Přihláška ke stravování</h3>
    <p>
        Přihláška ke stravování je žákům vydávána při nástupu do školního zařízení a platí po celou dobu školní
        docházky. Rodiče řádně celou přihlášku vyplní, včetně datumu a podpisu jednoho z rodičů. Pokud se ve školní
        jídelně stravují sourozenci, musí svoji přihlášku vyplnit každý z nich.
    </p>
    <h3>3. Výše stravného</h3>
    <table>
        <thead>
        <tr>
            <td>Typ stravy:</td>
            <td>Cena:</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Dopolední svačina v MŠ</td>
            <td>6 Kč</td>
        </tr>
        <tr>
            <td>Dopolední svačina a oběd v MŠ</td>
            <td>24 Kč</td>
        </tr>
        <tr>
            <td>Celodenní strava v MŠ</td>
            <td>30 Kč</td>
        </tr>
        <tr>
            <td>Oběd v ZŠ</td>
            <td>25 Kč</td>
        </tr>
        <tr>
            <td>Oběd pro zaměstnance školy</td>
            <td>26 Kč</td>
        </tr>
        <tr>
            <td>Oběd pro cizí strávníky "přes ulici"</td>
            <td>48 Kč</td>
        </tr>
        </tbody>
    </table>
    <h3>4. Příspěvky na stravování</h3>
    <p>
        V souladu s vyhláškou je stanoven jednotný termín pro úhradu stravného. Finanční prostředky k úhradě stravného
        se vybírají v kanceláři vedoucí školní jídelny vždy do 20. dne v měsíci. Pokud 20. Den vychází na dny volna,
        termín je k nejbližšímu předcházejícímu pracovnímu dni.
    </p>
    <p>
        Pokud dítě nemá řádně uhrazené stravování, nebude mu strava do provedení úhrady vydávána. Dále v souladu s
        nařízením ředitelky MŠ nemůže být dítě bez řádně uhrazeného stravování přijato do ZŠ.
    </p>
    <p>Příspěvky na stravování hradí rodiče předem na následující měsíc.</p>
    <h3>5. Přihlášení a odlášení stravování</h3>
    <p>Obědy se musí odhlašovat a přihlašovat nejpozději do 8:00h. ráno. Pokud není dítě
        odhlášeno včas, může dostat první den své absence jídlo do jídlonosiče domů.
        Částka za obědy nebude vrácena, pokud obědy nebudou řádně odhlášeny. Obědy se
        odhlašují a přihlašují v kanceláři ŠJ nebo na telefonním čísle: <strong>739 185 768</strong>.</p>
    <h3>6. Alergeny</h3>
    <p>Podle nařízení Evropského parlamentu a Rady EU 1169/2011/EU, čl. 44, odst. 1 a) o
        poskytování informací o potravinách spotřebitelům je povinnost informovat spotřebitele
        (strávníka) o alergenech vyskytujících se v nabízené potravině nebo v pokrmu. Předpis
        stanovuje specifické požadavky na označování alergenových složek, u kterých je prokázáno,
        že mohou vyvolat u spotřebitelů alergie nebo nesnášenlivosti představující nebezpečí pro
        zdraví. Je požadováno, aby veškeré pokrmy byly zřetelně označeny názvem příslušné
        alergenní složky, pokud není toto uvedeno přímo v názvu potraviny nebo jídla. Alergii mohou
        vyvolat všechny potraviny, ale EU specifikovala 14 hlavních alergenů, které podléhají
        legislativnímu značení:</p>
    <table>
        <thead>
        <tr>
            <td>Číslo alergenu:</td>
            <td>Název alergenu:</td>
            <td>Číslo alergenu:</td>
            <td>Název alergenu:</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1.</td>
            <td>Obilniny (lepek)</td>
            <td>8.</td>
            <td>Skořápkové plody</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Korýši</td>
            <td>9.</td>
            <td>Celer</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Vejce</td>
            <td>10.</td>
            <td>Hořčice</td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Ryby</td>
            <td>11.</td>
            <td>Sezam</td>
        </tr>
        <tr>
            <td>5.</td>
            <td>Arašídy</td>
            <td>12.</td>
            <td>Oxid siřičitý a siřičitany</td>
        </tr>
        <tr>
            <td>6.</td>
            <td>Sójové boby</td>
            <td>13.</td>
            <td>Vlčí bob (lupina)</td>
        </tr>
        <tr>
            <td>7.</td>
            <td>Mléko</td>
            <td>14.</td>
            <td>Měkkýši</td>
        </tr>
        </tbody>
    </table>
    a výrobky z nich
    <p>
        Tato povinnost se týká všech výrobců potravin, všech článků veřejného stravování, jako
        např. restaurací, jídelen, nemocnic, sociálních ústavů, prodejen, pekáren atd. U výrobku
        nebo u jídel ve veřejném stravování je povinnost vyznačit stanovený alergen, pokud ho
        výrobek nebo jídlo obsahuje.
    </p>
    <p>
        Zákonné ustanovení „jasně a zřetelně označit“ není sice nijak blíže specifikováno, lze však
        obecně odvodit, že se jedná o takové označení, které bude strávníka dostatečně jasným
        způsobem informovat o tom, že je alergenní složka v potravině přítomna, nebo že některá
        složka pochází z alergenu uvedeného ve zmíněném seznamu legislativně označených
        alergenů. Školní jídelna má pouze povinnost informační a nebude brát zřetel na přecitlivělost
        jednotlivých strávníků. Tuto skutečnost si musí každý strávník nebo jeho zákonný zástupce
        hlídat sám. Přítomnost alergenů bude přenesena z receptur a ingrediencí, které byly použity
        při výrobě pokrmů.
    </p>
    <p>
        Ve školní jídelně Základní školy Vraňany bude přítomnost alergenu označena na jídelním
        lístku číslem.
    </p>
    </div>
</main>
<footer>
    <?php include "../inc/footerAdmin.php" ?>
</footer>
<script>
    $("header nav a:nth-child(6)").addClass("active");
</script>
<?php include "../inc/scriptsAdmin.php" ?>
</body>
</html>

