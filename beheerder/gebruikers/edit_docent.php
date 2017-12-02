<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 30/11/2017
 * Time: 11:58
 */


$afkorting = ($_GET['afkorting']);

$db = db();
$docentQuery = $db->prepare("SELECT * FROM medewerker WHERE afkorting = :afkorting");
$docentQuery->bindParam(':afkorting' ,$afkorting, PDO::PARAM_STR);
$docentQuery->execute();
$docent = $docentQuery->fetch();


if (isset($_POST['submit'])) {


    /** gebruikersnaam */
    $error = array();


    /** Roepnaam */
    if (!isset($_POST['roepnaam']) || empty($_POST['roepnaam'])) {
        $error['roepnaam'] = ' Roepnaam is verplicht';
    }
    $roepnaam = filter_input(INPUT_POST, 'roepnaam', FILTER_SANITIZE_STRING);
    if (empty($roepnaam)) {
        $error['roepnaam'] = ' Het filteren van roepnaam ging verkeerd';
    }
    $roepnaam = strtolower($roepnaam);

    /** tussenvoegsel */
    $tussenvoegsel = filter_input(INPUT_POST, 'tussenvoegsel', FILTER_SANITIZE_STRING);
    if ($tussenvoegsel === false) {
        $error['tussenvoegsel'] = ' het filteren van tussenvoegsel ging verkeerd';
    }
    $tussenvoegsel = strtolower($tussenvoegsel);

    /** Achternaam */
    if (!isset($_POST['achternaam']) || empty($_POST['achternaam'])) {
        $error['achternaam'] = ' Achternaam is verplicht';
    }
    $achternaam = filter_input(INPUT_POST, 'achternaam', FILTER_SANITIZE_STRING);
    if (empty($achternaam)) {
        $error['achternaam'] = ' het filteren van achternaam ging verkeerd';
    }
    $achternaam = strtolower($achternaam);


    $functie = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
    /** geslacht */
    if (!isset($_POST['geslacht']) || empty($_POST['geslacht'])) {
        $error['geslacht'] = ' Geslacht is verplicht.';
    }
    $geslacht = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
    if (empty($geslacht)) {
        $error['geslacht'] = ' het filteren van geslacht ging verkeerd';
    }

    /** geboortedatum */
    if (!isset($_POST['geboortedatum']) || empty($_POST['geboortedatum'])) {
        $error['geboortedatum'] = ' Geboortedatum is verplicht';
    }
// check if given date can be converted to strtotime, if not. its false which means incorrect date.
    if (!strtotime($_POST['geboortedatum'])) {
        $error['geboortedatum'] = ' Geboortedatum moet een datum zijn.';
    }
    $geboortedatum = filter_input(INPUT_POST, 'geboortedatum', FILTER_SANITIZE_STRING);
    if (empty($geboortedatum)) {
        $error['geboortedatum'] = ' het filteren van geboortedatum ging verkeerd';
    }

    $locatie = filter_input(INPUT_POST, 'locatie', FILTER_SANITIZE_STRING);
    if ($locatie === false) {
        $error['locatie'] = ' het filteren van locatie ging verkeerd';
    }


    /** telefoon */
    if (!isset($_POST['telefoon']) || empty($_POST['telefoon'])) {
        $error['geslacht'] = ' telefoon is verplicht.';
    }
    $telefoon = filter_input(INPUT_POST, 'telefoon', FILTER_SANITIZE_STRING);
    if (empty($telefoon)) {
        $error['telefoon'] = ' het filteren van telefoon ging verkeerd';
    }

    if (count($error) === 0) {


        /**
         * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
         */
        $stmt = $db->prepare('
            UPDATE medewerker SET
            afkorting = :afkorting,
            roepnaam = :roepnaam, 
            tussenvoegsel = :tussenvoegsel, 
            achternaam = :achternaam,
            functie = :functie, 
            geslacht = :geslacht,
            geboortedatum = :geboortedatum, 
            locatie = :locatie,
            telefoon = :telefoon
            WHERE afkorting = :afkorting');

        $stmt->bindParam('afkorting', $afkorting, PDO::PARAM_STR);
        $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
        $stmt->bindParam('tussenvoegsel', $tussenvoegsel, PDO::PARAM_STR);
        $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
        $stmt->bindParam('functie', $functie, PDO::PARAM_STR);
        $stmt->bindParam('geslacht', $geslacht);
        $stmt->bindParam('geboortedatum', $geboortedatum);
        $stmt->bindParam('locatie', $locatie, PDO::PARAM_STR);
        $stmt->bindParam('telefoon', $telefoon);
        $stmt->bindParam('afkorting', $_GET ['afkorting']);
        $stmt->execute();
        redirect('/index.php?gebruiker=overzichtdocent');
    }
}


?>


<form action="<?= route('/index.php?gebruiker=editdocent&afkorting=' . $_GET['afkorting']) ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
<div class="form-group row">
    <label class="col-md-3 form-control-label">afkorting</label>
    <div class="col-md-9">
        <p class="form-control-static"><?= $docent['afkorting'] ?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="text-input">Naam</label>
    <div class="col-md-9">
        <input type="text" value="<?= $docent['roepnaam'] ?>" id="text-input" name="roepnaam" class="form-control"
               placeholder="<?= $docent['roepnaam'] ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
    <div class="col-md-9">
        <input type="text" value="<?= $docent['tussenvoegsel'] ?>" id="text-input" name="tussenvoegsel"
               class="form-control" placeholder="<?= $docent['tussenvoegsel'] ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
    <div class="col-md-9">
        <input type="text" value="<?= $docent['achternaam'] ?>" id="text-input" name="achternaam" class="form-control"
               placeholder="<?= $docent['achternaam'] ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="text-input">functie</label>
    <div class="col-md-9">
        <input type="text" value="<?= $docent['functie'] ?>" id="text-input" name="functie" class="form-control"
               placeholder="<?= $docent['functie'] ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
    <div class="col-md-9">
        <input type="text" value="<?= $docent['geslacht'] ?>" id="text-input" name="geslacht" class="form-control"
               placeholder="<?= $docent['geslacht'] ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
    <div class="col-md-9">
        <input type="date" value="<?= date('Y-m-d',strtotime($docent['geboortedatum'])) ?>" id="email-input" name="geboortedatum"
               class="form-control" placeholder="<?= $docent['geboortedatum'] ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="text-input">locatie</label>
    <div class="col-md-9">
        <input type="text" value="<?= $docent['locatie'] ?>" id="text-input" name="locatie" class="form-control"
               placeholder="<?= $docent['locatie'] ?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 form-control-label" for="text-input">telefoon</label>
    <div class="col-md-9">
        <input type="text" value="<?= $docent['telefoon'] ?>" id="text-input" name="telefoon" class="form-control"
               placeholder="<?= $docent['telefoon'] ?>">
    </div>
</div>
<?php if (isset($error)) { ?>
    <ul>
        <?php foreach ($error as $key => $error) { ?>
            <li><?= $key . ' : ' . $error; ?></li>
        <?php } ?>
    </ul>
<?php } ?>
<button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Account
    wijzigen
</button>
</form>
</html>