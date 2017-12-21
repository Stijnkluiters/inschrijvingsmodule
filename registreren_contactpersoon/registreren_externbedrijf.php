<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 08/12/2017
 * Time: 12:27
 */

include_once '../config.php';
if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
//Laat het bericht maar 1x zien per request!
    unset($_SESSION['message']);
}
if (isset($_POST['submit'])) {

    //secret recaptcha key:
    $secret_key = '6LcffjsUAAAAAMqC6IpdiCP7x1nWmJB-CDGnEia3';
    $response = post_request('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => $secret_key,
        'response' => $_POST['g-recaptcha-response']
    ]);
    $response = json_decode($response, true);


    if ($response['success']) {
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
            $error['herhaal_wachtwoord'] = ' Wachtwoord is niet het zelfde';
        }

        /** Bedrijfsgegevens */

        /** bedrijfsnaam */
        if (!isset($_POST['bedrijfsnaam']) || empty($_POST['bedrijfsnaam'])) {
            $error['bedrijfsnaam'] = ' bedrijfsnaam is verplicht';
        }
        $bedrijfsnaam = filter_input(INPUT_POST, 'bedrijfsnaam', FILTER_SANITIZE_STRING);
        if (empty($bedrijfsnaam)) {
            $error['bedrijfsnaam'] = ' Het filteren van bedrijfsnaam ging verkeerd';
        }
        $bedrijfsnaam = strtolower($bedrijfsnaam);

        /** branche */
        if (!isset($_POST['branche']) || empty($_POST['branche'])) {
            $error['branche'] = ' branche is verplicht';
        }
        $branche = filter_input(INPUT_POST, 'branche', FILTER_SANITIZE_STRING);
        if (empty($branche)) {
            $error['branche'] = ' Het filteren van branche ging verkeerd';
        }
        $branche = strtolower($branche);

        /** webadres */
        $webadres = filter_input(INPUT_POST, 'webadres', FILTER_SANITIZE_STRING);
        if ($webadres === false) {
            $error['webadres'] = ' het filteren van webadres ging verkeerd';
        }
        $webadres = strtolower($webadres);

        /** adres */
        if (!isset($_POST['adres']) || empty($_POST['adres'])) {
            $error['adres'] = ' adres  is verplicht';
        }
        $adres = filter_input(INPUT_POST, 'adres', FILTER_SANITIZE_STRING);
        if (empty($adres)) {
            $adres ['adres'] = ' Het filteren van adres ging verkeerd';
        }
        $adres = strtolower($adres);

        /** postcode */
        if (!isset($_POST['postcode']) || empty($_POST['postcode'])) {
            $error['postcode'] = ' postcode  is verplicht';
        }
        $postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
        if (empty($postcode)) {
            $postcode ['postcode'] = ' Het filteren van adres  ging verkeerd';
        }
        $postcode = strtolower($postcode);

        /** plaatsnaam */
        if (!isset($_POST['plaatsnaam']) || empty($_POST['plaatsnaam'])) {
            $error['plaatsnaam'] = ' plaatsnaam is verplicht';
        }
        $plaatsnaam = filter_input(INPUT_POST, 'plaatsnaam', FILTER_SANITIZE_STRING);
        if (empty($plaatsnaam)) {
            $plaatsnaam ['plaatsnaam'] = ' Het filteren van plaatsnaam  ging verkeerd';
        }
        $plaatsnaam = strtolower($plaatsnaam);


        /** Persoonsgegevens */

        /** roepnaam */
        if (!isset($_POST['roepnaam']) || empty($_POST['roepnaam'])) {
            $error['roepnaam'] = ' roepnaam is verplicht';
        }
        $roepnaam = filter_input(INPUT_POST, 'roepnaam', FILTER_SANITIZE_STRING);
        if (empty($roepnaam)) {
            $error['roepnaam'] = ' Het filteren van roepnaam ging verkeerd';
        }
        $roepnaam = strtolower($roepnaam);


        /** Tussenoegsel */
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

        /** Functie */
        if (!isset($_POST['functie']) || empty($_POST['functie'])) {
            $error['functie'] = ' Functie is verplicht';
        }
        $functie = filter_input(INPUT_POST, 'functie', FILTER_SANITIZE_STRING);
        if (empty($functie)) {
            $error['functie'] = ' het filteren van functie ging verkeerd';
        }
        $functie = strtolower($functie);

        /** Telefoonnummer */
        if (!isset($_POST['telefoonnummer']) || empty($_POST['telefoonnummer'])) {
            $error['telefoonnummer'] = ' Telefoonnummer is verplicht.';
        }

        $telefoonummer = filter_input(INPUT_POST, 'telefoonnummer', FILTER_SANITIZE_STRING);
        if (empty($telefoonummer)) {
            $error['telefoonnummer'] = ' het filteren van telefoonnummer ging verkeerd';
        }
        $telefoonummer = strtolower($telefoonummer);

        /** Email */
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $error['email'] = ' email is verplicht';
        }
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        if (empty($email)) {
            $error['email'] = ' het filteren van email ging verkeerd';
        }
        $email = strtolower($email);


        /** inventarisatie formulier */

        /** vakgebied */
        $vakgebied = filter_input(INPUT_POST, 'vakgebied', FILTER_SANITIZE_STRING);
        if ($vakgebied === false) {
            $error['vakgebied'] = ' het filteren van vakgebied ging verkeerd';
        }
        $vakgebied = strtolower($vakgebied);

        /** onderwerp */
        $onderwerp = filter_input(INPUT_POST, 'onderwerp', FILTER_SANITIZE_STRING);
        if ($onderwerp === false) {
            $error['onderwerp'] = ' het filteren van onderwerp ging verkeerd';
        }
        $onderwerp = strtolower($onderwerp);

        /** aantal_gastcolleges */
        $aantal_gastcolleges = filter_input(INPUT_POST, 'aantal_gastcolleges', FILTER_SANITIZE_STRING);
        if ($aantal_gastcolleges === false) {
            $error['aantal_gastcolleges'] = ' het filteren van aantal_gastcolleges ging verkeerd';
        }
        $aantal_gastcolleges = strtolower($aantal_gastcolleges);

        /** voorkeur_dag */
        $voorkeur_dag = filter_input(INPUT_POST, 'voorkeur_dag', FILTER_SANITIZE_STRING);
        if ($voorkeur_dag === false) {
            $error['voorkeur_dag'] = ' het filteren van voorkeur_dag ging verkeerd';
        }
        $voorkeur_dag = strtolower($voorkeur_dag);

        /** voorkeur_dagdeel */
        $voorkeur_dagdeel = filter_input(INPUT_POST, 'voorkeur_dagdeel', FILTER_SANITIZE_STRING);
        if ($voorkeur_dagdeel === false) {
            $error['voorkeur_dagdeel'] = ' het filteren van voorkeur_dagdeel ging verkeerd';
        }
        $voorkeur_dagdeel = strtolower($voorkeur_dagdeel);

        /** hulpmiddel */
        $hulpmiddel = filter_input(INPUT_POST, 'hulpmiddel', FILTER_SANITIZE_STRING);
        if ($hulpmiddel === false) {
            $error['hulpmiddel'] = ' het filteren van hulpmiddel ging verkeerd';
        }
        $hulpmiddel = strtolower($hulpmiddel);

        /** doelstelling */
        $doelstelling = filter_input(INPUT_POST, 'doelstelling', FILTER_SANITIZE_STRING);
        if ($doelstelling === false) {
            $error['doelstelling'] = ' het filteren van doelstelling ging verkeerd';
        }
        $doelstelling = strtolower($doelstelling);

        /** verwachting */
        $verwachting = filter_input(INPUT_POST, 'verwachting', FILTER_SANITIZE_STRING);
        if ($verwachting === false) {
            $error['verwachting'] = ' het filteren van verwachting ging verkeerd';
        }
        $dverwachting = strtolower($verwachting);


        /**
         * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
         */
        if (count($error) === 0) {





                $generatedPassword = generatePassword($wachtwoord);
                // gather rol_id


                $rolnaam = 'externbedrijf';

                $rol_id = check_if_role_exists($rolnaam);

                $db->beginTransaction();

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
                insert into inventarisatie 
               (vakgebied,
                onderwerp,
                aantal_gastcolleges,
                voorkeur_dag,
                voorkeur_dagdeel,
                hulpmiddel,
                doelstelling,
                verwachting)
                VALUES 
                (:vakgebied,
                :onderwerp,
                :aantal_gastcolleges,
                :voorkeur_dag,
                :voorkeur_dagdeel,
                :hulpmiddel,
                :doelstelling,
                :verwachting)
            ');
                $stmt->bindParam('vakgebied', $vakgebied, PDO::PARAM_LOB);
                $stmt->bindParam('onderwerp', $onderwerp, PDO::PARAM_LOB);
                $stmt->bindParam('aantal_gastcolleges', $aantal_gastcolleges, PDO::PARAM_LOB);
                $stmt->bindParam('voorkeur_dag', $voorkeur_dag, PDO::PARAM_LOB);
                $stmt->bindParam('voorkeur_dagdeel', $voorkeur_dagdeel, PDO::PARAM_LOB);
                $stmt->bindParam('hulpmiddel', $hulpmiddel, PDO::PARAM_LOB);
                $stmt->bindParam('doelstelling', $doelstelling, PDO::PARAM_LOB);
                $stmt->bindParam('verwachting', $verwachting, PDO::PARAM_LOB);
                $stmt->execute();
                $inventarisatie_id = $db->lastInsertId();


                $stmt = $db->prepare('insert into bedrijf
                (bedrijfsnaam)
                VALUE
                (:bedrijfsnaam)
                ');
                $stmt->bindParam('bedrijfsnaam', $bedrijfsnaam, PDO::PARAM_STR);
                $stmt->execute();
                $bedrijf_id = $db->lastInsertId();


                $stmt = $db->prepare('
                insert into branche 
               (bedrijf_id,
                inventarisatie_id,
                branche,
                webadres,
                adres,
                postcode,
                plaatsnaam)
                VALUES 
                (:bedrijf_id,
                :inventarisatie_id,
                :branche,
                :webadres,
                :adres,
                :postcode,
                :plaatsnaam)
            ');
                $stmt->bindParam('bedrijf_id', $bedrijf_id, PDO::PARAM_STR);
                $stmt->bindParam('inventarisatie_id', $inventarisatie_id, PDO::PARAM_STR);
                $stmt->bindParam('branche', $branche, PDO::PARAM_STR);
                $stmt->bindParam('webadres', $webadres, PDO::PARAM_STR);
                $stmt->bindParam('adres', $adres, PDO::PARAM_STR);
                $stmt->bindParam('postcode', $postcode, PDO::PARAM_STR);
                $stmt->bindParam('plaatsnaam', $plaatsnaam, PDO::PARAM_STR);
                $stmt->execute();
                $branche_id = $db->lastInsertId();

                $deleted = 1;
                $stmt = $db->prepare('
                insert into contactpersoon
               (
                account_id,
                branche_id,
                roepnaam,
                tussenvoegsel,
                achternaam,
                functie,
                telefoonnummer,
                email,
                deleted)
                VALUES 
                (:account_id,
                  :branche_id,
                  :roepnaam,
                  :tussenvoegsel,
                  :achternaam,
                  :functie,
                  :telefoonnummer,
                  :email,
                  :deleted)
            ');
                $stmt->bindParam('account_id', $account_id);
                $stmt->bindParam('branche_id', $branche_id);
                $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
                $stmt->bindParam('tussenvoegsel', $voorvoegsel, PDO::PARAM_STR);
                $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
                $stmt->bindParam('functie', $functie, PDO::PARAM_STR);
                $stmt->bindParam('telefoonnummer', $telefoonummer, PDO::PARAM_STR);
                $stmt->bindParam('email', $email, PDO::PARAM_STR);
                $stmt->bindParam('deleted',$deleted, PDO::PARAM_STR);
                $stmt->execute();

                $db->commit();
                redirect('/index.php');




        }
    } else {

        if(in_array('missing-input-response',$response['error-codes'])) {
            $error['error-codes'] = 'Recaptcha is verplicht';
        }

    }
}

?>

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ROC-midden Nederland evenementenmodule">
    <meta name="author" content="Richard Hilverts">
    <meta name="keyword"
          content="Bootstrap,Template,Open,Source,AngularJS,Angular,Angular2,Angular 2,Angular4,Angular 4,jQuery,CSS,HTML,RWD,Dashboard,React,React.js,Vue,Vue.js">
    <title>Inschrijfmodule</title>

    <!-- Icons -->
    <link href="../public/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Main styles for this application -->
    <link href="../public/css/style.css" rel="stylesheet">
    <link href="<?= route('../public/css/login.css'); ?>" rel="stylesheet"/>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="post" action="<?= route('/registreren_contactpersoon/registreren_externbedrijf.php'); ?>" class="container" id="register-form">
                <div class="card">
                    <div class="container-fluid">
                        <h1>Registreer
                            <small class="text-muted">Uw bedrijf</small>
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

                        <!-- Bedrijf -->

                        <!-- Bedrijsnaam Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-building"></i></span>
                            <input id="bedrijfsnaam" name="bedrijfsnaam" type="text"
                                   class="form-control<?= (isset($error['bedrijfsnaam'])) ? ' is-invalid' : '' ?>"
                                   required="required"
                                   value="<?= (isset($bedrijfsnaam)) ? $bedrijfsnaam : ''; ?>"
                                   placeholder="Uw bedrijfsnaam"
                                   aria-describedby="helpbedrijfsnaam"/>
                        </div>
                        <?php if (isset($error['bedrijfsnaam'])) { ?>
                            <!-- Roepnaam Helper -->
                            <span id="helpbedrijfsnaam"
                                  class="form-text bg-danger text-white"><?= $error['bedrijfsnaam']; ?></span>
                        <?php } ?>
                        <!-- branche Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-building"></i></span>
                            <input id="branche" name="branche" type="text"
                                   class="form-control<?= (isset($error['branche'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($branche)) ? $branche : ''; ?>"
                                   placeholder="Uw branche"
                                   aria-describedby="helpbranche"/>
                        </div>
                        <?php if (isset($error['branche'])) { ?>
                            <!-- Voervoegsel Helper -->
                            <span id="helpbranche"
                                  class="form-text bg-danger text-white"><?= $error['branche']; ?></span>
                        <?php } ?>

                        <!-- webadres Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-chrome"></i></span>
                            <input id="webadres" name="webadres" type="text"
                                   class="form-control<?= (isset($error['webadres'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($webadres)) ? $webadres : ''; ?>"
                                   placeholder="Uw webadres"
                                   aria-describedby="helpwebadres"/>
                        </div>
                        <?php if (isset($error['webadres'])) { ?>
                            <!-- webadres Helper -->
                            <span id="helpwebadres"
                                  class="form-text bg-danger text-white"><?= $error['webadres']; ?></span>
                        <?php } ?>

                        <!-- adres Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                            <input id="adres" name="adres" type="text"
                                   class="form-control<?= (isset($error['adres'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($adres)) ? $adres : ''; ?>"
                                   placeholder="Uw adres"
                                   aria-describedby="helpadres"/>
                        </div>
                        <?php if (isset($error['adres'])) { ?>
                            <!-- Telefoonnummer Helper -->
                            <span id="helpadres"
                                  class="form-text bg-danger text-white"><?= $error['adres']; ?></span>
                        <?php } ?>

                        <!-- postcode Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                            <input id="postcode" name="postcode" type="text"
                                   class="form-control<?= (isset($error['postcode'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($postcode)) ? $postcode : ''; ?>"
                                   placeholder="Uw postcode"
                                   aria-describedby="helppostcode"/>
                        </div>
                        <?php if (isset($error['postcode'])) { ?>
                            <!-- postcode Helper -->
                            <span id="helppostcode"
                                  class="form-text bg-danger text-white"><?= $error['postcode']; ?></span>
                        <?php } ?>

                          <!-- plaatsnaam Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                            <input id="plaatsnaam" name="plaatsnaam" type="text"
                                   class="form-control<?= (isset($error['plaatsnaam'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($plaatsnaam)) ? $plaatsnaam : ''; ?>"
                                   placeholder="Uw plaatsnaam"
                                   aria-describedby="helpplaatsnaam"/>
                        </div>
                        <?php if (isset($error['plaatsnaam'])) { ?>
                            <!-- plaatsnaam Helper -->
                            <span id="helpplaatsnaam"
                                  class="form-text bg-danger text-white"><?= $error['plaatsnaam']; ?></span>
                        <?php } ?>


                        <!-- Persoons gegevens -->

                        <!-- Roepnaam Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
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
                        <!-- tussenvoegsel Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
                            <input id="tussenvoegsel" name="tussenvoegsel" type="text"
                                   class="form-control<?= (isset($error['tussenvoegsel'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($tussenvoegsel)) ? $tussenvoegsel : ''; ?>"
                                   placeholder="Uw tussenvoegsel"
                                   aria-describedby="helptussenvoegsel"/>
                        </div>
                        <?php if (isset($error['tussenvoegsel'])) { ?>
                            <!-- tussenvoegsel Helper -->
                            <span id="helptussenvoegsel"
                                  class="form-text bg-danger text-white"><?= $error['tussenvoegsel']; ?></span>
                        <?php } ?>

                        <!-- Achternaam Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
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

                        <!-- Functie Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
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

                        <!-- email Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input id="email" name="email" type="email"
                                   class="form-control<?= (isset($error['email'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($email)) ? $email : ''; ?>"
                                   placeholder="Uw email-adres"
                                   aria-describedby="helpemail"/>
                        </div>
                        <?php if (isset($error['email'])) { ?>
                            <!-- email Helper -->
                            <span id="helpemail"
                                  class="form-text bg-danger text-white"><?= $error['email']; ?></span>
                        <?php } ?>


                        <!-- inventarisatie formulier -->


                        <!-- vakgebied Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="vakgebied" name="vakgebied" type="text"
                                   class="form-control input-lg<?= (isset($error['vakgebied'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($vakgebied)) ? $vakgebied : ''; ?>"
                                   placeholder="Uw vakgebied en een omschrijving ervan"
                                   aria-describedby="helpvakgebied"/>
                        </div>
                        <?php if (isset($error['achternaam'])) { ?>
                            <!-- Achternaam Helper -->
                            <span id="helpvakgebied"
                                  class="form-text bg-danger text-white"><?= $error['vakgebied']; ?></span>
                        <?php } ?>

                        <!-- onderwerp Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="onderwerp" name="onderwerp" type="text"
                                   class="form-control input-lg<?= (isset($error['onderwerp'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($onderwerp)) ? $onderwerp : ''; ?>"
                                   placeholder="Het onderwerp(en) van uw gastcollege"
                                   aria-describedby="helponderwerp"/>
                        </div>
                        <?php if (isset($error['onderwerp'])) { ?>
                            <!-- onderwerp Helper -->
                            <span id="helponderwerp"
                                  class="form-text bg-danger text-white"><?= $error['onderwerp']; ?></span>
                        <?php } ?>

                        <!-- aantal_gastcolleges Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="aantal_gastcolleges" name="aantal_gastcolleges" type="text"
                                   class="form-control input-lg<?= (isset($error['aantal_gastcolleges'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($aantal_gastcolleges)) ? $aantal_gastcolleges : ''; ?>"
                                   placeholder="Hoeveel gastcolleges/workshop wilt u op jaarbasis invullen?"
                                   aria-describedby="helpaantal_gastcolleges"/>
                        </div>
                        <?php if (isset($error['aantal_gastcolleges'])) { ?>
                            <!-- aantal_gastcolleges Helper -->
                            <span id="helpaantal_gastcolleges"
                                  class="form-text bg-danger text-white"><?= $error['aantal_gastcolleges']; ?></span>
                        <?php } ?>

                        <!-- voorkeur_dag Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="voorkeur_dag" name="voorkeur_dag" type="text"
                                   class="form-control input-lg<?= (isset($error['voorkeur_dag'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($voorkeur_dag)) ? $voorkeur_dag : ''; ?>"
                                   placeholder="Welke dagen hebben uw voorkeur?"
                                   aria-describedby="helpvoorkeur_dag"/>
                        </div>
                        <?php if (isset($error['voorkeur_dag'])) { ?>
                            <!-- voorkeur_dag Helper -->
                            <span id="helpvoorkeur_dag"
                                  class="form-text bg-danger text-white"><?= $error['voorkeur_dag']; ?></span>
                        <?php } ?>

                        <!-- voorkeur_dagdeel Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="voorkeur_dagdeel" name="voorkeur_dagdeel" type="text"
                                   class="form-control input-lg<?= (isset($error['voorkeur_dagdeel'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($voorkeur_dagdeel)) ? $voorkeur_dagdeel : ''; ?>"
                                   placeholder="Welk dagdeel heeft uw voorkeur?"
                                   aria-describedby="helpvoorkeur_dagdeel"/>
                        </div>
                        <?php if (isset($error['voorkeur_dagdeel'])) { ?>
                            <!-- voorkeur_dagdeel Helper -->
                            <span id="helpvoorkeur_dagdeel"
                                  class="form-text bg-danger text-white"><?= $error['voorkeur_dagdeel']; ?></span>
                        <?php } ?>

                        <!-- hulpmiddel Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="hulpmiddel" name="hulpmiddel" type="text"
                                   class="form-control input-lg<?= (isset($error['hulpmiddel'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($hulpmiddel)) ? $hulpmiddel : ''; ?>"
                                   placeholder="Welke hulpmiddelen heeft u nodig voor uw gastcollege ? (beamer, flipover e.d.) "
                                   aria-describedby="helphulpmiddel"/>
                        </div>
                        <?php if (isset($error['hulpmiddel'])) { ?>
                            <!-- voorkeur_dagdeel Helper -->
                            <span id="helphulpmiddel"
                                  class="form-text bg-danger text-white"><?= $error['hulpmiddel']; ?></span>
                        <?php } ?>

                        <!-- doelstelling Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="doelstelling" name="doelstelling" type="text"
                                   class="form-control input-lg<?= (isset($error['doelstelling'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($doelstelling)) ? $doelstelling : ''; ?>"
                                   placeholder="Welke doelstelling(en) zou u graag behaald zien ?"
                                   aria-describedby="helpdoelstelling"/>
                        </div>
                        <?php if (isset($error['doelstelling'])) { ?>
                            <!-- doelstelling Helper -->
                            <span id="helpdoelstelling"
                                  class="form-text bg-danger text-white"><?= $error['doelstelling']; ?></span>
                        <?php } ?>

                        <!-- verwachting Form -->
                        <div class="input-group mt-3">
                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                            <input id="verwachting" name="verwachting" type="text"
                                   class="form-control input-lg<?= (isset($error['verwachting'])) ? ' is-invalid' : '' ?>"
                                   value="<?= (isset($verwachting)) ? $verwachting : ''; ?>"
                                   placeholder="Wanneer heeft de activiteit aan uw verwachting voldaan?"
                                   aria-describedby="helpverwachting"/>
                        </div>
                        <?php if (isset($error['verwachting'])) { ?>
                            <!-- doelstelling Helper -->
                            <span id="helpdverwachting"
                                  class="form-text bg-danger text-white"><?= $error['verwachting']; ?></span>
                        <?php } ?>
                        <hr/>
                        <?php if( isset($error['error-codes']) ) { ?>
                            <hr/>
                            <div class="card-footer bg-danger">
                                <small class="text-center"><?= ucfirst($error['error-codes']); ?></small>
                            </div>
                            <?php

                        }
                        ?>
                        <div class="g-recaptcha" data-sitekey="6LcffjsUAAAAAK_qsbG5FQm4UnceLL2O5ztC0Kp7"></div>
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
<script src='https://www.google.com/recaptcha/api.js'></script>

<!-- Bootstrap and necessary plugins -->
<script src="public/js/jquery.min.js"></script>
<script src="public/js/propper.js"></script>
<script src="public/js/bootstrap.js"></script>
<script src="public/js/pace.js"></script>

<!-- Plugins and scripts required by all views -->
<script src="public/js/Chart.min.js"></script>

<!-- GenesisUI main scripts -->

<script src="public/js/app.js"></script>

<!-- Plugins and scripts required by this views -->

<!-- Custom scripts required by this view -->
<script src="public/js/main.js"></script>

<!--Script for order table leerling-->
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="public/js/notify.js"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });
    <?php

    if(isset($message)) { ?>
    $.notify("<?= $message; ?>",{
        className: 'info'
    });
    <?php } ?>


</script>
</html>