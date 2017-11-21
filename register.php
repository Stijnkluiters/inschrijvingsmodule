<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 16-11-2017
 * Time: 13:29
 */
include 'config.php';

if (isset($_POST['submit'])) {
    $db = db();

    $stmt = $db->prepare('select id from gebruiker where gebruikersnaam = :gebruikersnaam');
    $stmt->bindParam(':gebruikersnaam', $_POST['gebruikersnaam']);
    $stmt->execute();
    $rows = $stmt->rowCount();
    if ($rows === 0) {

        $wachtwoord = generatePassword($_POST['wachtwoord']);



        $db->beginTransaction();

        $stmt = $db->prepare('
            insert into gebruiker 
            (roepnaam,voorvoegsel,achternaam,email,gebruikersnaam,wachtwoord,geboortedatum,geslacht)
            VALUES 
            (:roepnaam,:voorvoegsel,:achternaam,:email,:gebruikersnaam,:wachtwoord,:geboortedatum,:geslacht)
        ');
        $stmt->bindParam('roepnaam', $_POST['roepnaam']);
        $stmt->bindParam('voorvoegsel', $_POST['roepnaam']);
        $stmt->bindParam('achternaam', $_POST['achternaam']);
        $stmt->bindParam('email', $_POST['email']);
        $stmt->bindParam('gebruikersnaam', $_POST['gebruikersnaam']);
        $stmt->bindParam('wachtwoord', $wachtwoord);
        $stmt->bindParam('geboortedatum', $_POST['geboortedatum']);
        $stmt->bindParam('geslacht', $_POST['geslacht']);
        $stmt->execute();

        $db->commit();
        $success = 'Gebruiker is aangemaakt met naam ' . $_POST['gebruikersnaam'];
    } else {
        $error = sprintf('Er bestaat al een gebruiker met gebruikersnaam %s',$_POST['gebruikersnaam']);
    }


}


?>
<html>
<head>

    <link rel="shortcut icon" href="<?= route('/public/img/favicon.ico') ?>" type="image/vnd.microsoft.icon"/>

    <link href="<?= route('/public/css/login.css') ?>" rel="stylesheet"/>
    <link href="<?= route('/public/css/style.css') ?>" rel="stylesheet"/>
</head>
<body style="background: none;">


<div class="container">

    <div class="row">

        <div class="col-md-12">
            <div class="wrap">
                <p class="form-title">
                    Registreren</p>

                <?php
                if(isset($success)) {
                    success($success);
                }


                if(isset($error)) {
                    error($error);
                }
                ?>

                <blockquote style="background: black;color: white;">
                    Deze pagina is tijdelijk voor het testen bedoeld zodat er beheerders kunnen aangemaakt worden.
                </blockquote>
                <form method="post" action="<?= route('/register.php'); ?>" class="login">
                    <div class="form-group">
                        <label for="roepnaam">Roepnaam</label>
                        <input type="text" name="roepnaam" class="form-control" id="roepnaam"
                               placeholder="uw roepnaam" required="required"/>
                    </div>
                    <div class="form-group">
                        <label for="voorvoegsel">Voorvoegsel</label>
                        <input type="text" name="voorvoegsel" class="form-control" id="voorvoegsel"
                               placeholder="uw voorvoegsel">
                    </div>
                    <div class="form-group">
                        <label for="achternaam">Achternaam</label>
                        <input type="text" class="form-control" name="achternaam" id="achternaam"
                               placeholder="uw achternaam" required="required">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                               placeholder="uw e-mailadres">
                        <small id="emailHelp" class="form-text text-muted">
                            Dit e-mailadres wordt niet gebruikt voor het inloggen.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="gebruikersnaam">Gebruikersnaam</label>
                        <input type="text" class="form-control" id="gebruikersnaam"  name="gebruikersnaam" aria-describedby="gebruikersnaamHelp"
                               placeholder="Gebruikersnaam">
                        <small id="gebruikersnaamHelp" class="form-text text-muted">
                            gebruikersnaam wordt gebruikt voor het inloggen voor uw applicatie.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="wachtwoord">Wachtwoord</label>
                        <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" aria-describedby="wachtwoordHelp"
                               placeholder="Uw wachtwoord">
                        <small id="emailHelp" class="form-text text-muted">
                            pas op & onthoud wat je invoerd! er is nog geen password recovery geschreven.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Geboortedatum</label>
                        <input type="date" class="form-control" id="geboortedatum" name="geboortedatum" aria-describedby="geboortedatumHelp"
                               placeholder="uw geboortedatum"/>
                    </div>
                    <div class="form-group">
                        <div class="radio">
                            <label><input type="radio" name="geslacht" value="man">Man</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="geslacht" value="vrouw">Vrouw</label>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>