<?php

/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 16-11-2017
 * Time: 12:29
 */
include '../config.php';
if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    //Laat het bericht maar 1x zien per request!
    unset($_SESSION['message']);
}
if (isset($_POST['submit'])) {
    /**
     * Filter input from user, which is required in order to continue the request->post.
     */
    $error = array();

    // controleren of de gebruikersnaam al bestaat.
    $db = db();
    /** Wachtwoord */
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

    if (count($error) === 0) {
        $generatedPassword = generatePassword($wachtwoord);
        // gather rol_id

        $rolnaam = 'leerling';

        $rol_id = check_if_role_exists($rolnaam);

        $stmt = $db->prepare('
                UPDATE account SET wachtwoord = :wachtwoord WHERE account_id = :account_id
            ');
        $stmt->bindParam('account_id', $_SESSION[authenticationSessionName]);
        $stmt->bindParam('wachtwoord', $generatedPassword);
        $stmt->execute();

        redirect('/student/index.php', 'Uw wachtwoord is gewijzigd!');
    }
}


?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ROC-midden Nederland evenementenmodule">
    <meta name="author" content="Tristan van Heusden, Johan van de Wetering">
    <meta name="keyword"
          content="Bootstrap,Template,Open,Source,AngularJS,Angular,Angular2,Angular 2,Angular4,Angular 4,jQuery,CSS,HTML,RWD,Dashboard,React,React.js,Vue,Vue.js">
    <title>Inschrijfmodule</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= route('/public/css/style.css'); ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= route('/public/css/student.css'); ?>" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="post" action="<?= route('/student/wijzigen_wachtwoord.php'); ?>" class="container" id="register-form">
                <div class="card">
                    <div class="container-fluid">
                        <h2>Wijzig je wachtwoord</h2>
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
                        <br><button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Wachtwoord wijzigen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>