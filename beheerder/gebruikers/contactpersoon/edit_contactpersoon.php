<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 04/12/2017
 * Time: 15:02
 */


$db = db();
$contact_id = filter_var(filter_input(INPUT_GET, 'contact_id', FILTER_SANITIZE_STRING), FILTER_VALIDATE_INT);


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

    /** functie */
    if (!isset($_POST['functie']) || empty($_POST['functie'])) {
        $error['functie'] = ' functie is verplicht.';
    }
    $functie = filter_input(INPUT_POST, 'functie', FILTER_SANITIZE_STRING);
    if (empty($functie)) {
        $error['functie'] = ' het filteren van functie ging verkeerd';
    }


    /** telefoonnummer */
    if (!isset($_POST['telefoon']) || empty($_POST['telefoon'])) {
        $error['telefoon'] = ' telefoonnummer is verplicht.';
    }
    $telefoonnummer = filter_input(INPUT_POST, 'telefoon', FILTER_SANITIZE_STRING);
    if (empty($telefoonnummer)) {
        $error['telefoon'] = ' het filteren van telefoon ging verkeerd';
    }

    /** email */
    if (!isset($_POST['Email']) || empty($_POST['Email'])) {
        $error['Email'] = ' email is verplicht.';
    }
    $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_STRING);
    if (empty($email)) {
        $error['Email'] = ' het filteren van email ging verkeerd';
    }


    if (count($error) === 0) {


        /**
         * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
         */
        $stmt = $db->prepare('
            UPDATE contactpersoon SET
            contact_id = :contact_id,
            roepnaam = :roepnaam, 
            tussenvoegsel = :tussenvoegsel, 
            achternaam = :achternaam,
            functie = :functie, 
            `telefoonnummer` = :telefoonnummer,
            `email` = :email
            WHERE contact_id = :contact_id');

        $stmt->bindParam('contact_id', $contact_id, PDO::PARAM_STR);
        $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
        $stmt->bindParam('tussenvoegsel', $tussenvoegsel, PDO::PARAM_STR);
        $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
        $stmt->bindParam('functie', $functie, PDO::PARAM_STR);
        $stmt->bindParam('telefoonnummer', $telefoonnummer, PDO::PARAM_STR);
        $stmt->bindParam('email', $email, PDO::PARAM_STR);
        $stmt->bindParam('contact_id', $contact_id, PDO::PARAM_STR);
        $stmt->execute();
        redirect('/index.php?gebruiker=overzichtcontactpersonen', $roepnaam . ' is aangepast.');
    } else {
        dump($error);
        exit;
    }
}

if (isset($_POST['account_wijzigen'])) {

    $error = [];


    if (isset($_POST['gebruikersnaam']) && !empty($_POST['gebruikersnaam'])) {
        $gebruikersnaam = strtolower(filter_input(INPUT_POST, 'gebruikersnaam', FILTER_SANITIZE_STRING));
        if (strlen($gebruikersnaam) < 6) {
            $error['gebruikersnaam'] = 'Gebruikersnaam moet langer dan 6 karakters zijn';
        }
    } else {
        $error['gebruikersnaam'] = 'Gebruikersnaam is verplicht';
    }


    if (isset($_POST['wachtwoord']) && !empty($_POST['wachtwoord'])) {
        $wachtwoord = filter_input(INPUT_POST, 'wachtwoord', FILTER_SANITIZE_STRING);
        if (strlen($wachtwoord) < 7) {
            $error['wachtwoord'] = 'Wachtwoord moet langer dan 7 karakters zijn';
        }

        // controleer voor speciale tekens in het wachtwoord
        if (!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $wachtwoord)) {
            $error['wachtwoord'] = ' Er zitten geen speciale tekens in het wachtwoord';
        }


    } else {
        $error['wachtwoord'] = 'Wachtwoord is verplicht';
    }


    if (isset($_POST['account_id']) && !empty($_POST['account_id'])) {
        $account_id = intval(strtolower(filter_input(INPUT_POST, 'account_id', FILTER_SANITIZE_STRING)));
        if (!filter_var($account_id, FILTER_VALIDATE_INT)) {
            $error['account_id'] = 'Account id moet een nummeriek getal zijn.';
        }
        $stmt = $db->prepare('select account_id from account where account_id = ?');
        $stmt->execute(array($account_id));
        $rowCount = $stmt->rowCount();
        if (!$rowCount) {
            $error['account_id'] = 'Account moet in de database beschikbaar zijn.';
        }
    } else {
        $error['account_id'] = 'account_id is verplicht';
    }


    if (count($error) === 0) {


        $stmt = $db->prepare('update account set
        gebruikersnaam = ?,
        wachtwoord = ? 
        WHERE account_id = ?');
        if ($stmt->execute(array(
            $gebruikersnaam,
            generatePassword($wachtwoord),
            $account_id
        ))) {
            $success = success('Uw account is aangepast!');
        } else {
            $success = error('Er is iets misgegaan');
        }

    }
}
$contactQuery = $db->prepare("SELECT * FROM contactpersoon join account on contactpersoon.account_id = account.account_id WHERE contact_id = :contact_id");
$contactQuery->bindParam('contact_id', $contact_id, PDO::PARAM_STR);
$contactQuery->execute();
$contact = $contactQuery->fetch();

?>

<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <strong>Leerling</strong>
                <small>wijzigen</small>
            </div>
            <div class="card-body">
                <form action="<?= route('/index.php?gebruiker=editcontactpersoon&contact_id=' . $_GET['contact_id']) ?>"
                      method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $contact['roepnaam'] ?>" id="text-input" name="roepnaam"
                                   class="form-control"
                                   placeholder="<?= $contact['roepnaam'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $contact['tussenvoegsel'] ?>" id="text-input"
                                   name="tussenvoegsel"
                                   class="form-control" placeholder="<?= $contact['tussenvoegsel'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $contact['achternaam'] ?>" id="text-input" name="achternaam"
                                   class="form-control"
                                   placeholder="<?= $contact['achternaam'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">functie</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $contact['functie'] ?>" id="text-input" name="functie"
                                   class="form-control"
                                   placeholder="<?= $contact['functie'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">telefoon</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $contact['telefoonnummer'] ?>" id="text-input" name="telefoon"
                                   class="form-control"
                                   placeholder="<?= $contact['telefoonnummer'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Email</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $contact['email'] ?>" id="text-input" name="Email"
                                   class="form-control"
                                   placeholder="<?= $contact['email'] ?>">
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

                <!--/.row-->
            </div>
        </div>

    </div>
    <!--/.col-->
    <div class="col-sm-6">

        <div class="card">
            <div class="card-header">
                <strong>Account</strong>
                <small>Aanpassen</small>
            </div>
            <div class="card-body">
                <form action="<?= route('/index.php?gebruiker=editcontactpersoon&contact_id=' . $contact_id) ?>"
                      method="post">
                    <?php

                    if (isset($success)) {
                        $success;
                    }

                    ?>
                    <input type="hidden" value="<?= $contact['account_id']; ?>" name="account_id"/>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="gebruikersnaam">Gebruikersnaam</label>
                                <input type="text"
                                       class="form-control <?= (isset($error['gebruikersnaam'])) ? 'is-invalid' : '' ?>"
                                       id="gebruikersnaam"
                                       name="gebruikersnaam"
                                       placeholder="<?= $contact['gebruikersnaam']; ?>"
                                       value="<?= $contact['gebruikersnaam']; ?>"
                                       aria-describedby="helpGebruikernaam"
                                />

                                <?php if (isset($error['gebruikersnaam'])) { ?>
                                    <!-- Gebruikersnaam helper -->
                                    <span id="helpGebruikernaam"
                                          class="form-text bg-danger text-white"><?= $error['gebruikersnaam']; ?></span>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                    <!--/.row-->

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form-group">
                                <label for="wachtwoord">Wachtwoord</label>
                                <input type="text"
                                       class="form-control <?= (isset($error['wachtwoord'])) ? 'is-invalid' : '' ?>"
                                       id="wachtwoord"
                                       name="wachtwoord"
                                       placeholder="Uw wachtwoord"
                                       aria-describedby="helpWachtwoord"
                                />
                                <?php if (isset($error['wachtwoord'])) { ?>
                                    <!-- Wachtwoord helper -->
                                    <span id="helpWachtwoord"
                                          class="form-text bg-danger text-white"><?= $error['wachtwoord']; ?></span>
                                <?php } ?>
                            </div>

                        </div>


                    </div>
                    <!--/.row-->

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form-group">
                                <button type="submit" name="account_wijzigen" class="btn btn-block btn-primary mb-3"
                                        id="account_wijzigen" value="">account wijzigen
                                </button>
                            </div>

                        </div>

                    </div>
                    <!--/.row-->
                </form>
            </div>
        </div>

    </div>
    <!--/.col-->
