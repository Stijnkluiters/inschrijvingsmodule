<?php

/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 16-11-2017
 * Time: 13:29
 */
include 'config.php';

if (isset($_POST['submit'])) {
    /**
     * Filter input from user, which is required in order to continue the request->post.
     */
    /** gebruikersnaam */
    $error = array();
    if (!isset($_POST['gebruikersnaam']) || empty($_POST['gebruikersnaam'])) {
        $error['gebruikersnaam'] = ' Gebruikersnaam is verplicht';
    }
    if (strlen($_POST['gebruikersnaam']) < 4) {
        $error['gebruikersnaam'] = ' gebruikersnaam moet langer dan 5 karakters zijn';
    }
    $gebruikersnaam = filter_input(INPUT_POST, 'gebruikersnaam', FILTER_SANITIZE_STRING);
    $gebruikersnaam = strtolower($gebruikersnaam);
    // controleren of de gebruikersnaam al bestaat.
    $db = db();
    $stmt = $db->prepare('select * from account where gebruikersnaam = :gebruikernaam');
    $stmt->bindParam('gebruikernaam', $gebruikersnaam, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $error['gebruikersnaam'] = ' er bestaat al een gebruiker met de naam: ' . $gebruikersnaam;
    }
    /** Wachtwoord */
    if ($gebruikersnaam === false) {
        $error['gebruikersnaam'] = ' het filteren van gebruikersnaam ging verkeerd';
    }
    if (!isset($_POST['wachtwoord']) || empty($_POST['wachtwoord'])) {
        $error['wachtwoord'] = ' Wachtwoord is verplicht';
    }
    // wachtwoord moet minimaal 8 karakters hebben
    if (strlen($_POST['wachtwoord']) < 8) {
        $error['wachtwoord'] = ' Wachtwoord moet minimaal 8 of meer karakters hebben';
    }
    // wachtwoord moet minimaal 1 hoofdletter hebben
    if ($_POST['wachtwoord'] === strtolower($_POST['wachtwoord'])) {
        $error['wachtwoord'] = ' Wachtwoord moet minimaal 1 hoofdletter hebben';
    }
    $wachtwoord = filter_input(INPUT_POST, 'wachtwoord', FILTER_SANITIZE_STRING);
    if ($wachtwoord === false) {
        $error['wachtwoord'] = ' Het filteren van wachtwoord ging verkeerd';
    }


    /** Herhaaling Wachtwoord */
    if (!isset($_POST['herhaal_wachtwoord']) || empty($_POST['herhaal_wachtwoord'])) {
        $error['herhaal_wachtwoord'] = ' Herhalend wachtwoord is verplicht';
    }
    if ($_POST['wachtwoord'] !== $_POST['herhaal_wachtwoord']) {
        $error['herhaal_wachtwoord'] = ' 2 velden zijn niet het zelfde';
    }
    /** Roepnaam */
    if (!isset($_POST['roepnaam']) || empty($_POST['roepnaam'])) {
        $error['roepnaam'] = ' Roepnaam is verplicht';
    }
    $roepnaam = filter_input(INPUT_POST, 'roepnaam', FILTER_SANITIZE_STRING);
    if (empty($roepnaam)) {
        $error['roepnaam'] = ' Het filteren van roepnaam ging verkeerd';
    }
    $roepnaam = strtolower($roepnaam);


    /** Voorvoegsel */
    $voorvoegsel = filter_input(INPUT_POST, 'voorvoegsel', FILTER_SANITIZE_STRING);
    if ($voorvoegsel === false) {
        $error['voorvoegsel'] = ' het filteren van voorvoegsel ging verkeerd';
    }
    $voorvoegsel = strtolower($voorvoegsel);

    /** Achternaam */
    if (!isset($_POST['achternaam']) || empty($_POST['achternaam'])) {
        $error['achternaam'] = ' Achternaam is verplicht';
    }
    $achternaam = filter_input(INPUT_POST, 'achternaam', FILTER_SANITIZE_STRING);
    if (empty($achternaam)) {
        $error['achternaam'] = ' het filteren van achternaam ging verkeerd';
    }
    $achternaam = strtolower($achternaam);


    /** Telefoonnummer */
    if (!isset($_POST['telefoonnummer']) || empty($_POST['telefoonnummer'])) {
        $error['telefoonnummer'] = ' Telefoonnummer is verplicht.';
    }

    $telefoonummer = filter_input(INPUT_POST, 'telefoonnummer', FILTER_SANITIZE_STRING);
    if (empty($telefoonummer)) {
        $error['telefoonnummer'] = ' het filteren van telefoonnummer ging verkeerd';
    }
    $telefoonummer = strtolower($telefoonummer);
    /**
     * TODO: check if actual phonenumber; strip - signs.
     */

    /** geboortedatum */
    if (!isset($_POST['geboortedatum']) || empty($_POST['geboortedatum'])) {
        $error['geboortedatum'] = ' Geboortedatum is verplicht';
    }
    // check if given date can be converted to strtotime, if not. its false which means incorrect date.
    if (!strtotime($_POST['geboortedatum'])) {
        $error['geboortedatum'] = ' Ge  boortedatum moet een datum zijn.';
    }
    $geboortedatum = filter_input(INPUT_POST, 'geboortedatum', FILTER_SANITIZE_STRING);
    if (empty($geboortedatum)) {
        $error['geboortedatum'] = ' het filteren van geboortedatum ging verkeerd';
    }

    /** geslacht */
    if (!isset($_POST['geslacht']) || empty($_POST['geslacht'])) {
        $error['geslacht'] = ' Geslacht is verplicht.';
    }
    $geslacht = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
    if (empty($geslacht)) {
        $error['geslacht'] = ' het filteren van geslacht ging verkeerd';
    }

    /** Afkorting */
    if (!isset($_POST['afkorting']) || empty($_POST['afkorting'])) {
        $error['afkorting'] = ' Afkorting is verplicht';
    }
    $afkorting = filter_input(INPUT_POST, 'afkorting', FILTER_SANITIZE_STRING);
    if (empty($afkorting)) {
        $error['afkorting'] = ' het filteren van afkorting ging verkeerd';
    }
    $afkorting = strtolower($afkorting);

    /** Functie */
    if (!isset($_POST['functie']) || empty($_POST['functie'])) {
        $error['functie'] = ' Functie is verplicht';
    }
    $functie = filter_input(INPUT_POST, 'functie', FILTER_SANITIZE_STRING);
    if (empty($functie)) {
        $error['functie'] = ' het filteren van functie ging verkeerd';
    }
    $functie = strtolower($functie);

    /** Locatie */
    if (!isset($_POST['locatie']) || empty($_POST['locatie'])) {
        $error['locatie'] = ' locatie is verplicht';
    } else {
        $locatie = filter_input(INPUT_POST, 'locatie', FILTER_SANITIZE_STRING);
        if (empty($locatie)) {
            $error['locatie'] = ' het filteren van locatie ging verkeerd';
        }
        $locatie = strtolower($locatie);
    }



    /**
     * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
     */
    if (count($error) === 0) {
        $generatedPassword = generatePassword($wachtwoord);
        // gather rol_id


        $rolnaam = 'beheerder';

        $rol_id = check_if_role_exists($rolnaam);

            $stmt = $db->prepare('
                insert into account 
               (gebruikersnaam,wachtwoord,rol_id)
                VALUES 
                (:gebruikersnaam,:wachtwoord,:rol_id)
            ');
            $stmt->bindParam('gebruikersnaam', $gebruikersnaam);
            $stmt->bindParam('wachtwoord', $generatedPassword);
            $stmt->bindParam('rol_id', $rol_id);
            $stmt->execute();

            $stmt = $db->prepare('select account_id from account where gebruikersnaam = :gebruikersnaam');
            $stmt->bindParam('gebruikersnaam', $gebruikersnaam);
            $stmt->execute();
            $account_id = $stmt->fetchAll()[0]['account_id'];

            $stmt = $db->prepare('
                insert into medewerker 
               (
                afkorting,
                account_id,
                roepnaam,
                tussenvoegsel,
                achternaam,
                functie,
                geslacht,
                geboortedatum,
                locatie,
                telefoon)
                VALUES 
                (
                  :afkorting,
                  :account_id,
                  :roepnaam,
                  :tussenvoegsel,
                  :achternaam,
                  :functie,
                  :geslacht,
                  :geboortedatum,
                  :locatie,
                  :telefoon
                )
            ');
            $stmt->bindParam('account_id',$account_id);
            $stmt->bindParam('afkorting',$afkorting);
            $stmt->bindParam('roepnaam', $roepnaam);
            $stmt->bindParam('tussenvoegsel', $voorvoegsel, PDO::PARAM_STR);
            $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
            $stmt->bindParam('functie', $functie, PDO::PARAM_STR);
            $stmt->bindParam('geslacht', $geslacht, PDO::PARAM_STR);
            $stmt->bindParam('geboortedatum', $geboortedatum, PDO::PARAM_STR);
            $stmt->bindParam('locatie', $locatie, PDO::PARAM_STR);
            $stmt->bindParam('telefoon', $telefoonummer, PDO::PARAM_STR);
            $stmt->execute();


            login($gebruikersnaam,$wachtwoord);

            redirect('/index.php');

    }
}


?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ROC-midden Nederland evenementenmodule">
    <meta name="author" content="Stijn Kluiters, Johan van de Wetering">
    <meta name="keyword"
          content="Bootstrap,Template,Open,Source,AngularJS,Angular,Angular2,Angular 2,Angular4,Angular 4,jQuery,CSS,HTML,RWD,Dashboard,React,React.js,Vue,Vue.js">
    <title>Inschrijfmodule</title>

    <!-- Icons -->
    <link href="public/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Main styles for this application -->
    <link href="public/css/style.css" rel="stylesheet">
    <link href="<?= route('/public/css/login.css'); ?>" rel="stylesheet"/>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="post" action="<?= route('/register.php'); ?>" class="container" id="register-form">
                <div class="card">
                    <div class="container-fluid">
                        <h1>Registreer
                            <small class="text-muted">jouw beheerder</small>
                        </h1>
                        <!-- Gebruikersnaam Form -->
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input id="gebruikersnaam" name="gebruikersnaam" type="text"
                                   class="form-control<?= (isset($error['gebruikersnaam'])) ? ' is-invalid' : '' ?> "
                                   required="required"
                                   placeholder="Gebruikersnaam"
                                   value="<?= (isset($gebruikersnaam)) ? $gebruikersnaam : ''; ?>"
                                   aria-describedby="helpGebruikernaam"/>
                        </div>
                        <?php if (isset($error['gebruikersnaam'])) { ?>
                            <!-- Gebruikersnaam helper -->
                            <span id="helpGebruikernaam"
                                  class="form-text bg-danger text-white"><?= $error['gebruikersnaam']; ?></span>
                        <?php } ?>
                        <!-- Wachtwoord Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input id="wachtwoord" name="wachtwoord" type="password"
                                   class="form-control<?= (isset($error['wachtwoord'])) ? ' is-invalid' : '' ?>"
                                   required="required" placeholder="Uw wachtwoord" aria-describedby="helpWachtwoord"/>
                        </div>
                        <?php if (isset($error['wachtwoord'])) { ?>
                            <!-- Wachtwoord helper -->
                            <span id="helpWachtwoord"
                                  class="form-text bg-danger text-white"><?= $error['wachtwoord']; ?></span>
                        <?php } ?>
                        <!-- repeat_wachtwoord Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input id="herhaal_wachtwoord" name="herhaal_wachtwoord" type="password"
                                   class="form-control<?= (isset($error['herhaal_wachtwoord'])) ? ' is-invalid' : '' ?>"
                                   required="required" placeholder="Herhaal uw wachtwoord"
                                   aria-describedby="helpHerhaalWachtwoord"/>
                        </div>
                        <?php if (isset($error['herhaal_wachtwoord'])) { ?>
                            <!-- repeat_wachtwoord helper -->
                            <span id="helpHerhaalWachtwoord"
                                  class="form-text bg-danger text-white"><?= $error['herhaal_wachtwoord']; ?></span>
                        <?php } ?>

                        <!-- Roepnaam Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-male"></i></span>
                            <input id="roepnaam" name="roepnaam" type="text"
                                   class="form-control<?= (isset($error['roepnaam'])) ? ' is-invalid' : '' ?>"
                                   required="required"
                                   value="<?= (isset($roepnaam)) ? $roepnaam : ''; ?>"
                                   placeholder="Uw roepnaam"
                                   aria-describedby="helpRoepnaam"/>
                        </div>
                        <?php if (isset($error['roepnaam'])) { ?>
                            <!-- Roepnaam Helper -->
                            <span id="helpRoepnaam"
                                  class="form-text bg-danger text-white"><?= $error['roepnaam']; ?></span>
                        <?php } ?>
                        <!-- Voorvoegsel Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-male"></i></span>
                            <input id="voorvoegsel" name="voorvoegsel" type="text"
                                   class="form-control<?= (isset($error['voorvoegsel'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($voorvoegsel)) ? $voorvoegsel : ''; ?>"
                                   placeholder="Uw voorvoegsel"
                                   aria-describedby="helpVoorvoegsel"/>
                        </div>
                        <?php if (isset($error['voorvoegsel'])) { ?>
                            <!-- Voervoegsel Helper -->
                            <span id="helpVoorvoegsel"
                                  class="form-text bg-danger text-white"><?= $error['voorvoegsel']; ?></span>
                        <?php } ?>

                        <!-- Achternaam Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-male"></i></span>
                            <input id="achternaam" name="achternaam" type="text"
                                   class="form-control<?= (isset($error['achternaam'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($achternaam)) ? $achternaam : ''; ?>"
                                   placeholder="Uw achternaam"
                                   aria-describedby="helpAchternaam"/>
                        </div>
                        <?php if (isset($error['achternaam'])) { ?>
                            <!-- Achternaam Helper -->
                            <span id="helpAchternaam"
                                  class="form-text bg-danger text-white"><?= $error['achternaam']; ?></span>
                        <?php } ?>

                        <!-- Telefoonnummer Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input id="telefoonnummer" name="telefoonnummer" type="text"
                                   class="form-control<?= (isset($error['telefoonnummer'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($telefoonnummer)) ? $telefoonnummer : ''; ?>"
                                   placeholder="Uw telefoonnummer"
                                   aria-describedby="helpTelefoonnummer"/>
                        </div>
                        <?php if (isset($error['telefoonnummer'])) { ?>
                            <!-- Telefoonnummer Helper -->
                            <span id="helpTelefoonnummer"
                                  class="form-text bg-danger text-white"><?= $error['telefoonnummer']; ?></span>
                        <?php } ?>

                        <!-- Geboortedatum Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input id="geboortedatum" name="geboortedatum" type="date"
                                   class="form-control<?= (isset($error['geboortedatum'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($geboortedatum)) ? $geboortedatum : ''; ?>"
                                   placeholder="dd-mm-jjjj"
                                   aria-describedby="helpDate"/>
                        </div>
                        <?php if (isset($error['geboortedatum'])) { ?>
                            <!-- Geboortedatum Helper -->
                            <span id="helpDate"
                                  class="form-text bg-danger text-white"><?= $error['geboortedatum']; ?></span>
                        <?php } ?>



                        <!-- Geslacht Form -->
                        <div class="input-group form-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-genderless"></i></span>
                            <select id="geslacht" name="geslacht" required="required"
                                    class="form-control<?= (isset($error['geslacht'])) ? ' is-invalid' : '' ?>"
                                    aria-describedby="helpGeslacht">
                                <option <?= (!isset($geslacht)) ? 'selected="selected"' : '' ?> value="">Kies uw
                                    geslacht
                                </option>
                                <option <?= (isset($geslacht) && $geslacht == 'man') ? 'selected="selected"' : '' ?>
                                        value="Man">Man
                                </option>
                                <option <?= (isset($geslacht) && $geslacht == 'vrouw') ? 'selected="selected"' : '' ?>
                                        value="Vrouw">Vrouw
                                </option>
                                <option <?= (isset($geslacht) &&
                                    $geslacht == 'onbekend') ? 'selected="selected"' : '' ?> value="onbekend">Onbekend
                                </option>
                            </select>
                        </div>
                        <?php if (isset($error['geslacht'])) { ?>
                            <!-- Geslacht Helper -->
                            <span id="helpGeslacht"
                                  class="form-text bg-danger text-white"><?= $error['geslacht']; ?></span>
                        <?php } ?>


                        <!-- Afkorting Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-dedent"></i></span>
                            <input id="afkorting" name="afkorting" type="text"
                                   class="form-control<?= (isset($error['afkorting'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($afkorting)) ? $afkorting : ''; ?>"
                                   placeholder="Uw afkorting"
                                   aria-describedby="helpAfkorting"/>
                        </div>
                        <?php if (isset($error['afkorting'])) { ?>
                            <!-- Afkorting Helper -->
                            <span id="helpAfkorting"
                                  class="form-text bg-danger text-white"><?= $error['afkorting']; ?></span>
                        <?php } ?>

                        <!-- Functie Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input id="functie" name="functie" type="text"
                                   class="form-control<?= (isset($error['functie'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($functie)) ? $functie : ''; ?>"
                                   placeholder="Uw functie"
                                   aria-describedby="helpFunctie"/>
                        </div>
                        <?php if (isset($error['functie'])) { ?>
                            <!-- Functie Helper -->
                            <span id="helpFunctie"
                                  class="form-text bg-danger text-white"><?= $error['functie']; ?></span>
                        <?php } ?>


                        <!-- Locatie Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input id="locatie" name="locatie" type="text"
                                   class="form-control<?= (isset($error['locatie'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($locatie)) ? $locatie : ''; ?>"
                                   placeholder="Uw locatie"
                                   aria-describedby="helpLocatie"/>
                        </div>
                        <?php if (isset($error['locatie'])) { ?>
                            <!-- Locatie Helper -->
                            <span id="helpLocatie"
                                  class="form-text bg-danger text-white"><?= $error['locatie']; ?></span>
                        <?php } ?>
                        <hr/>
                        <button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Account
                            aanmaken
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>