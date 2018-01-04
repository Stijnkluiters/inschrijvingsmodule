<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 30/11/2017
 * Time: 11:58
 */


$afkorting = filter_input(INPUT_GET, 'afkorting', FILTER_SANITIZE_STRING);


$db = db();
$docentQuery = $db->prepare("SELECT * FROM medewerker m LEFT JOIN account a ON a.account_id = m.account_id WHERE afkorting = :afkorting");
$docentQuery->bindParam('afkorting', $afkorting, PDO::PARAM_STR);
$docentQuery->execute();
$docent = $docentQuery->fetch();


if( isset($_POST[ 'submit' ]) )
{
    /** gebruikersnaam */
    $error = array();


    /** Roepnaam */
    if( !isset($_POST[ 'roepnaam' ]) || empty($_POST[ 'roepnaam' ]) )
    {
        $error[ 'roepnaam' ] = ' Roepnaam is verplicht';
    }
    $roepnaam = filter_input(INPUT_POST, 'roepnaam', FILTER_SANITIZE_STRING);
    if( empty($roepnaam) )
    {
        $error[ 'roepnaam' ] = ' Het filteren van roepnaam ging verkeerd';
    }
    $roepnaam = strtolower($roepnaam);

    /** tussenvoegsel */
    $tussenvoegsel = filter_input(INPUT_POST, 'tussenvoegsel', FILTER_SANITIZE_STRING);
    if( $tussenvoegsel === false )
    {
        $error[ 'tussenvoegsel' ] = ' het filteren van tussenvoegsel ging verkeerd';
    }
    $tussenvoegsel = strtolower($tussenvoegsel);

    /** Achternaam */
    if( !isset($_POST[ 'achternaam' ]) || empty($_POST[ 'achternaam' ]) )
    {
        $error[ 'achternaam' ] = ' Achternaam is verplicht';
    }
    $achternaam = filter_input(INPUT_POST, 'achternaam', FILTER_SANITIZE_STRING);
    if( empty($achternaam) )
    {
        $error[ 'achternaam' ] = ' het filteren van achternaam ging verkeerd';
    }
    $achternaam = strtolower($achternaam);

    /** functie */
    if( !isset($_POST[ 'functie' ]) || empty($_POST[ 'functie' ]) )
    {
        $error[ 'functie' ] = ' functie is verplicht.';
    }
    $functie = filter_input(INPUT_POST, 'functie', FILTER_SANITIZE_STRING);
    if( empty($functie) )
    {
        $error[ 'functie' ] = ' het filteren van functie ging verkeerd';
    }

    /** geslacht */
    if( !isset($_POST[ 'geslacht' ]) || empty($_POST[ 'geslacht' ]) )
    {
        $error[ 'geslacht' ] = ' Geslacht is verplicht.';
    }
    $geslacht = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
    if( empty($geslacht) )
    {
        $error[ 'geslacht' ] = ' het filteren van geslacht ging verkeerd';
    }

    /** geboortedatum */
    if( !isset($_POST[ 'geboortedatum' ]) || empty($_POST[ 'geboortedatum' ]) )
    {
        $error[ 'geboortedatum' ] = ' Geboortedatum is verplicht';
    }
// check if given date can be converted to strtotime, if not. its false which means incorrect date.
    if( !strtotime($_POST[ 'geboortedatum' ]) )
    {
        $error[ 'geboortedatum' ] = ' Geboortedatum moet een datum zijn.';
    }
    $geboortedatum = filter_input(INPUT_POST, 'geboortedatum', FILTER_SANITIZE_STRING);
    if( empty($geboortedatum) )
    {
        $error[ 'geboortedatum' ] = ' het filteren van geboortedatum ging verkeerd';
    }

    /** locatie */
    if( !isset($_POST[ 'locatie' ]) || empty($_POST[ 'locatie' ]) )
    {
        $error[ 'locatie' ] = ' locatie is verplicht.';
    }
    $locatie = filter_input(INPUT_POST, 'locatie', FILTER_SANITIZE_STRING);
    if( $locatie == false )
    {
        $error[ 'locatie' ] = ' het filteren van locatie ging verkeerd';
    }


    /** telefoon */
    if( !isset($_POST[ 'telefoon' ]) || empty($_POST[ 'telefoon' ]) )
    {
        $error[ 'geslacht' ] = ' telefoon is verplicht.';
    }
    $telefoon = filter_input(INPUT_POST, 'telefoon', FILTER_SANITIZE_STRING);
    if( empty($telefoon) )
    {
        $error[ 'telefoon' ] = ' het filteren van telefoon ging verkeerd';
    }

    if( count($error) === 0 )
    {


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
        $stmt->bindParam('afkorting', $_GET [ 'afkorting' ]);
        $stmt->execute();
        redirect('/index.php?gebruiker=overzichtmedewerker', 'Medewerker ' . $roepnaam . ' aangepast!');
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

        // controleer voor speciale tekens in het wachtwoord
        if(!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $wachtwoord)) {
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
                <strong>Medewerker</strong>
                <small>Aanpassen</small>
            </div>
            <div class="card-body">

                <form action="<?= route('/index.php?gebruiker=editmedewerker&afkorting=' . $_GET[ 'afkorting' ]) ?>"
                      method="post"
                      enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">afkorting</label>
                        <div class="col-md-9">
                            <p class="form-control-static"><?= $docent[ 'afkorting' ] ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $docent[ 'roepnaam' ] ?>" id="text-input" name="roepnaam"
                                   class="form-control"
                                   placeholder="<?= $docent[ 'roepnaam' ] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $docent[ 'tussenvoegsel' ] ?>" id="text-input"
                                   name="tussenvoegsel"
                                   class="form-control" placeholder="<?= $docent[ 'tussenvoegsel' ] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $docent[ 'achternaam' ] ?>" id="text-input" name="achternaam"
                                   class="form-control"
                                   placeholder="<?= $docent[ 'achternaam' ] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">functie</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $docent[ 'functie' ] ?>" id="text-input" name="functie"
                                   class="form-control"
                                   placeholder="<?= $docent[ 'functie' ] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $docent[ 'geslacht' ] ?>" id="text-input" name="geslacht"
                                   class="form-control"
                                   placeholder="<?= $docent[ 'geslacht' ] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
                        <div class="col-md-9">
                            <input type="date" value="<?= date('Y-m-d', strtotime($docent[ 'geboortedatum' ])) ?>"
                                   id="email-input"
                                   name="geboortedatum"
                                   class="form-control" placeholder="<?= $docent[ 'geboortedatum' ] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">locatie</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $docent[ 'locatie' ] ?>" id="text-input" name="locatie"
                                   class="form-control"
                                   placeholder="<?= $docent[ 'locatie' ] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">telefoon</label>
                        <div class="col-md-9">
                            <input type="text" value="<?= $docent[ 'telefoon' ] ?>" id="text-input" name="telefoon"
                                   class="form-control"
                                   placeholder="<?= $docent[ 'telefoon' ] ?>">
                        </div>
                    </div>
                    <button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Medewerker
                        wijzigen
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-6">

        <div class="card">
            <div class="card-header">
                <strong>Account</strong>
                <small>Aanpassen</small>
            </div>
            <div class="card-body">
                <form action="<?= route('/index.php?gebruiker=editleerling&leerlingnummer=' . $docent['afkorting']) ?>" method="post">
                    <?php
                    if( isset($success) )
                    {
                        $success;
                    }

                    ?>
                    <input type="hidden" value="<?= $docent[ 'afkorting' ]; ?>" name="account_id"/>
                    <div class="row">


                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="gebruikersnaam">Gebruikersnaam</label>
                                <input type="text"
                                       class="form-control <?= (isset($error[ 'gebruikersnaam' ])) ? 'is-invalid' : '' ?>"
                                       id="gebruikersnaam"
                                       name="gebruikersnaam"
                                       placeholder="<?= $docent[ 'gebruikersnaam' ]; ?>"
                                       value="<?= $docent[ 'gebruikersnaam' ]; ?>"
                                       aria-describedby="helpGebruikernaam"
                                />

                                <?php if( isset($error[ 'gebruikersnaam' ]) ) { ?>
                                    <!-- Gebruikersnaam helper -->
                                    <span id="helpGebruikernaam"
                                          class="form-text bg-danger text-white"><?= $error[ 'gebruikersnaam' ]; ?></span>
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
                                       class="form-control <?= (isset($error[ 'wachtwoord' ])) ? 'is-invalid' : '' ?>"
                                       id="wachtwoord"
                                       name="wachtwoord"
                                       placeholder="Uw wachtwoord"
                                       aria-describedby="helpWachtwoord"
                                />
                                <?php if( isset($error[ 'wachtwoord' ]) ) { ?>
                                    <!-- Wachtwoord helper -->
                                    <span id="helpWachtwoord"
                                          class="form-text bg-danger text-white"><?= $error[ 'wachtwoord' ]; ?></span>
                                <?php } ?>
                            </div>

                        </div>


                    </div>
                    <!--/.row-->

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form-group">
                                <button type="submit" name="account_wijzigen"
                                        class="btn btn-block btn-primary mb-3"
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
</div>
<!--/.col-->

<?php if( isset($error) ) { ?>
    <ul>
        <?php foreach ($error as $key => $error) { ?>
            <li><?= $key . ' : ' . $error; ?></li>
        <?php } ?>
    </ul>
<?php } ?>
