<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 22-11-2017
 * Time: 13:08
 */
$leerlingnummer = filter_var(filter_input(INPUT_GET,'leerlingnummer',FILTER_SANITIZE_STRING),FILTER_VALIDATE_INT);
$db = db();
$leerlingQuery = $db->prepare("SELECT * FROM leerling l LEFT JOIN account a ON a.account_id = l.account_id WHERE leerlingnummer = :leerlingnummer");
$leerlingQuery->bindParam('leerlingnummer', $leerlingnummer, PDO::PARAM_STR);
$leerlingQuery->execute();
$leerling = $leerlingQuery->fetch();


if (isset($_POST['submit'])) {
    //var_dump($_POST);
    //exit;
    /**
     * Filter input from user, which is required in order to continue the request->post.
     */
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
    /** Voorvoegsel */

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
    /** geslacht */
    if (!isset($_POST['geslacht']) || empty($_POST['geslacht'])) {
        $error['geslacht'] = ' Geslacht is verplicht.';
    }
    $geslacht = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
    if (empty($geslacht)) {
        $error['geslacht'] = ' het filteren van geslacht ging verkeerd';
    }
    //**poscode*/
    if (!isset($_POST['postcode']) || empty($_POST['postcode'])) {
        $error['postcode'] = ' postcode is verplicht.';
    }
    $postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
    if (empty($postcode)) {
        $error['postcode'] = ' het filteren van postcode ging verkeerd';
    }
    /**plaats*/
    if (!isset($_POST['plaats']) || empty($_POST['plaats'])) {
        $error['plaats'] = ' plaats is verplicht.';
    }
    $plaats = filter_input(INPUT_POST, 'plaats', FILTER_SANITIZE_STRING);
    if (empty($plaats)) {
        $error['plaats'] = ' het filteren van plaats ging verkeerd';
    }
    /**opleiding*/
    if (!isset($_POST['opleiding']) || empty($_POST['opleiding'])) {
        $error['opleiding'] = ' opleiding is verplicht.';
    }
    $opleiding = filter_input(INPUT_POST, 'opleiding', FILTER_SANITIZE_STRING);
    if (empty($opleiding)) {
        $error['opleiding'] = ' het filteren van opleiding ging verkeerd';
    }


    if (count($error) === 0) {

        /**
         * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
         */
        $stmt = $db->prepare('
            UPDATE leerling SET
            roepnaam = :roepnaam, 
            tussenvoegsel = :tussenvoegsel, 
            achternaam = :achternaam,
            opleiding = :opleiding, 
            geboortedatum = :geboortedatum,
            postcode = :postcode,
            plaats = :plaats, 
            geslacht = :geslacht
            WHERE leerlingnummer = :leerlingnummer');

        $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
        $stmt->bindParam('tussenvoegsel', $tussenvoegsel, PDO::PARAM_STR);
        $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
        $stmt->bindParam('opleiding', $opleiding);
        $stmt->bindParam('geboortedatum', $geboortedatum);
        $stmt->bindParam('postcode', $postcode);
        $stmt->bindParam('plaats', $plaats);
        $stmt->bindParam('geslacht', $geslacht);
        $stmt->bindParam('leerlingnummer', $_GET ['leerlingnummer']);
        $stmt->execute();
        redirect('/index.php?gebruiker=overzichtleerling','Leerling ' . $roepnaam . ' aangepast!');

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
        $wachtwoord = strtolower(filter_input(INPUT_POST, 'wachtwoord', FILTER_SANITIZE_STRING));
        if (strlen($wachtwoord) < 7) {
            $error['wachtwoord'] = 'Wachtwoord moet langer dan 7 karakters zijn';
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
        if($stmt->execute(array(
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


?>
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <strong>Credit Card</strong>
                <small>Form</small>
            </div>
            <div class="card-body">
                <form action="<?= route('/index.php?gebruiker=editleerling&leerlingnummer=' . $leerlingnummer) ?>"
                      method="post" class="form-horizontal">
                    <div class="form-group">
                        <label for="leerlingnummer" class="col-6">Leerlingnummer</label>
                        <div id="leerlingnummer" class="col-6">
                            <p><?= $leerling['leerlingnummer'] ?></p>
                            <input type="hidden" name="account_id" value="<?= $leerling['account_id']; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $leerling['roepnaam'] ?>" id="text-input" name="roepnaam"
                                   class="form-control" placeholder="<?= $leerling['roepnaam'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $leerling['tussenvoegsel'] ?>" id="text-input"
                                   name="tussenvoegsel" class="form-control"
                                   placeholder="<?= $leerling['tussenvoegsel'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $leerling['achternaam'] ?>" id="text-input" name="achternaam"
                                   class="form-control" placeholder="<?= $leerling['achternaam'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $leerling['geslacht'] ?>" id="text-input" name="geslacht"
                                   class="form-control" placeholder="<?= $leerling['geslacht'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
                        <div class="col-md-9">
                            <input type="date" value="<?= date('Y-m-d', strtotime($leerling['geboortedatum'])); ?>"
                                   id="email-input" name="geboortedatum" class="form-control"
                                   placeholder="<?= $leerling['geboortedatum'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Postcode</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $leerling['postcode'] ?>" id="text-input" name="postcode"
                                   class="form-control" placeholder="<?= $leerling['postcode'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Woonplaats</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $leerling['plaats'] ?>" id="text-input" name="plaats"
                                   class="form-control" placeholder="<?= $leerling['plaats'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Opleiding</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $leerling['opleiding'] ?>" id="text-input" name="opleiding"
                                   class="form-control" placeholder="<?= $leerling['opleiding'] ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Begin van de opleiding</label>
                        <div class="col-md-9">
                            <input type="date" value="<?= date('Y-m-d', strtotime($leerling['begindatum'])); ?>"
                                   id="text-input" name="begindatum" class="form-control"
                                   placeholder="<?= $leerling['begindatum'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Eind van de opleiding</label>
                        <div class="col-md-9">
                            <input type="date" value="<?= date('Y-m-d', strtotime($leerling['einddatum'])); ?>"
                                   id="text-input" name="einddatum" class="form-control"
                                   placeholder="<?= $leerling['einddatum'] ?>">
                        </div>
                    </div>
                    <button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Profiel
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
                <form action="<?= route('/index.php?gebruiker=editleerling&leerlingnummer=' . $leerlingnummer) ?>"
                      method="post">
                    <?php

                    if(isset($success)) {
                         $success;
                    }

                    ?>
                    <input type="hidden" value="<?= $leerling['account_id']; ?>" name="account_id"/>
                    <div class="row">


                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="gebruikersnaam">Gebruikersnaam</label>
                                <input type="text"
                                       class="form-control <?= (isset($error['gebruikersnaam'])) ? 'is-invalid' : '' ?>"
                                       id="gebruikersnaam"
                                       name="gebruikersnaam"
                                       placeholder="<?= $leerling['gebruikersnaam']; ?>"
                                       value="<?= $leerling['gebruikersnaam']; ?>"
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

</div>