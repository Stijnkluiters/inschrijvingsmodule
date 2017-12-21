<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 20-12-2017
 * Time: 17:25
 */

include 'config.php';

$account_id = filter_var(filter_input(INPUT_GET, 'account_id', FILTER_SANITIZE_STRING), FILTER_VALIDATE_INT);
$db = db();
$gebruikerQuery = $db->prepare("SELECT * FROM medewerker WHERE account_id = :account_id");
$gebruikerQuery->bindParam('account_id', $account_id, PDO::PARAM_STR);
$gebruikerQuery->execute();
$gebruiker = $gebruikerQuery->fetch();

if (isset($_POST['submit'])) {

    /** gebruikersnaam */
    $error = array();


//    /** Afkorting */
//    if (!isset($_POST['afkorting']) || empty($_POST['afkorting'])) {
//        $error['afkorting'] = ' Afkorting is verplicht';
//    }
//    $afkorting = filter_input(INPUT_POST, 'afkorting', FILTER_SANITIZE_STRING);
//    if (empty($afkorting)) {
//        $error['afkorting'] = ' Het filteren van afkorting ging verkeerd';
//    }
//    $afkorting = strtolower($afkorting);

    /** Roepnaam */
    if (!isset($_POST['roepnaam']) || empty($_POST['roepnaam'])) {
        $error['roepnaam'] = ' Roepnaam is verplicht';
    }
    $roepnaam = filter_input(INPUT_POST, 'roepnaam', FILTER_SANITIZE_STRING);
    if (empty($roepnaam)) {
        $error['roepnaam'] = ' Het filteren van roepnaam ging verkeerd';
    }
    $roepnaam = strtolower($roepnaam);

    /** Tussenvoegsel */
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

    $functie = filter_input(INPUT_POST, 'functie', FILTER_SANITIZE_STRING);
    /** Functie */
    if (!isset($_POST['functie']) || empty($_POST['functie'])) {
        $error['functie'] = ' Functie is verplicht';
    }
     if (empty($functie)) {
        $error['functie'] = ' het filteren van functie ging verkeerd';
    }
    $functie = strtolower($functie);

    /** Geslacht */
    if (!isset($_POST['geslacht']) || empty($_POST['geslacht'])) {
        $error['geslacht'] = ' Geslacht is verplicht';
    }
    $geslacht = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
    if (empty($geslacht)) {
        $error['geslacht'] = ' het filteren van geslacht ging verkeerd';
    }
    $geslacht = strtolower($geslacht);

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
        $error['geboortedatum'] = 'het filteren van geboortedatum ging verkeerd';
    }

    /** Locatie */
    if (!isset($_POST['locatie']) || empty($_POST['locatie'])) {
        $error['locatie'] = ' Locatie is verplicht';
    }
    $locatie = filter_input(INPUT_POST, 'locatie', FILTER_SANITIZE_STRING);
    if (empty($locatie)) {
        $error['locatie'] = ' het filteren van locatie ging verkeerd';
    }
    $locatie = strtolower($locatie);

    /** Telefoonnummer */
    if (!isset($_POST['telefoon']) || empty($_POST['telefoon'])) {
        $error['telefoon'] = ' Telefoon is verplicht';
    }
    $telefoon = filter_input(INPUT_POST, 'telefoon', FILTER_SANITIZE_STRING);
    if (empty($telefoon)) {
        $error['telefoon'] = ' het filteren van telefoon ging verkeerd';
    }
    $telefoon = strtolower($telefoon);

    if (count($error) === 0) {
        /* Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.*/
        $stmt = $db->prepare('
        UPDATE medewerker SET
        roepnaam = :roepnaam,
        tussenvoegsel = :tussenvoegsel, 
        achternaam = :achternaam,
        functie = :functie,
        geslacht = :geslacht,
        geboortedatum = :geboortedatum,
        locatie = :locatie,
        telefoon = :telefoon
        WHERE account_id = :account_id');

        $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
        $stmt->bindParam('tussenvoegsel', $tussenvoegsel, PDO::PARAM_STR);
        $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
        $stmt->bindParam('functie', $functie, PDO::PARAM_STR);
        $stmt->bindParam('geslacht', $geslacht);
        $stmt->bindParam('geboortedatum', $geboortedatum);
        $stmt->bindParam('locatie', $locatie, PDO::PARAM_STR);
        $stmt->bindParam('telefoon', $telefoon, PDO::PARAM_STR);
        $stmt->bindParam('account_id', filter_var($_GET['account_id'],FILTER_SANITIZE_STRING));
        $stmt->execute();
        redirect('/profiel.php?=profiel&account_id='.$account_id, 'Gebruiker ' . $gebruiker['roepnaam'] . ' aangepast!');
        // TODO: make the error variables readable for the user.
    }
}
?>
<link href="public/css/style.css" rel="stylesheet">
<header class="app-header navbar">
    <a class="navbar-brand" href="#"></a>
        <div class='pull-right control-group'>
            <a href="<?= route('/index.php') ?>" class="btn btn-primary">Terug</a>
        </div>
</header>




<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= route('/profiel.php?=profiel&account_id='.$gebruiker['account_id']) ?>"
                      method="post" class="form-horizontal">
                    <div class="form-group">
                        <h3><label for="roepnaam" class="col-8">Evenementen overzicht van <?= ucfirst($gebruiker['roepnaam']); ?> </label></h3>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Naam</label>
                    <div class="col-md-9">
                        <input type="text" value="<?= $gebruiker['roepnaam'] ?>" id="text-input" name="roepnaam"
                            class="form-control" placeholder="<?= $gebruiker['roepnaam'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
                    <div class="col-md-9">
                        <input type="text" value="<?= $gebruiker['tussenvoegsel'] ?>" id="text-input"
                            name="tussenvoegsel" class="form-control"
                            placeholder="<?= $gebruiker['tussenvoegsel'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
                    <div class="col-md-9">
                        <input type="text" value="<?= $gebruiker['achternaam'] ?>" id="text-input" name="achternaam"
                        class="form-control" placeholder="<?= $gebruiker['achternaam'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Functie</label>
                    <div class="col-md-9">
                        <input type="text" value="<?= $gebruiker['functie'] ?>" id="text-input" name="functie"
                        class="form-control" placeholder="<?= $gebruiker['functie'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
                    <div class="col-md-9">
                        <input type="text" value="<?= $gebruiker['geslacht'] ?>"
                        id="text-input" name="geslacht" class="form-control"
                        placeholder="<?= $gebruiker['geslacht'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
                    <div class="col-md-9">
                        <input type="date" value="<?= date('Y-m-d', strtotime($gebruiker['geboortedatum'])) ?>"
                               id="email-input"
                               name="geboortedatum"
                               class="form-control" placeholder="<?= $gebruiker['geboortedatum'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Locatie</label>
                    <div class="col-md-9">
                        <input type="text" value="<?= $gebruiker['functie'] ?>"
                        id="text-input" name="locatie" class="form-control"
                        placeholder="<?= $gebruiker['functie']?> ">
                    </div>
                </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Telefoon</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $gebruiker['telefoon'] ?>"
                                   id="text-input" name="telefoon" class="form-control"
                                   placeholder="<?= $gebruiker['telefoon']?> ">
                        </div>
                    </div>

                    <button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Profiel wijzigen
                    </button>
            </form>
        </div>
    </div>

</div>




