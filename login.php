<?php

/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 16-11-2017
 * Time: 13:29
 */
include 'config.php';

if( isset($_POST[ 'submit' ]) )
{



    $username = $_POST[ 'gebruikersnaam' ];
    $password = $_POST[ 'wachtwoord' ];

    if( strlen($username) === 0 )
    {
        $error = 'gebruikersnaam is verplicht.';
    }
    if( strlen($password) < 8 )
    {
        $error = 'wachtwoord moet langer dan 7 karakters zijn';
    }

    if( !isset($error) )
    {
        $output = login($username, $password);

        if( $output === 'NOUSER' || $output === 'INVALIDPASSWORD' )
        {
            $error = 'incorrecte gegevens, probeer het opnieuw.';
        }
        else
        {
            //alle inloggegevens kloppen, gebruiker is nu ingelogd. tijd om door te sturen naar het dashboard.
            redirect('index.php');
        }

    }

}

?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ROC-midden Nederland evenementenmodule">
    <meta name="author" content="Stijn Kluiters, ...">
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
<div class="container" style="min-height: 100vh">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card p-4">
                    <form method="post" action="<?= route('/login.php'); ?>">
                        <div class="card-body">
                            <h1>Inloggen</h1>
                            <p class="text-muted">login met jou gebruikersnaam en wachtwoord</p>
                            <div class="input-group mb-3">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="text" class="form-control" placeholder="Gebruikersnaam"
                                       name="gebruikersnaam" required="required"/>
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" class="form-control" placeholder="Wachtwoord" name="wachtwoord"
                                       autocomplete="off" required="required"/>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" name="submit" class="btn btn-primary px-4">Login</button>
                                </div>
                                <!--<div class="col-6 text-right">-->
                                <!--    <button type="button" class="btn btn-link px-0">Forgot password?</button>-->
                                <!--</div>-->

                            </div>
                            <?php if( isset($error) ) { ?>
                            <hr/>
                                <div class="card-footer bg-danger">
                                    <small class="text-center"><?= ucfirst($error); ?></small>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                    </form>
                </div>

                <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                    <div class="card-body text-center">
                        <div>
                            <h2>Registreren</h2>
                            <p>klik hier om te registreren, het is de bedoeling dat de beheerder jou registratie
                                goedkeurt. Het registreer proces is tijdelijk zo en wordt later nog uitgebreid.</p>
                            <a href="<?= route('/register.php'); ?>" type="button" class="btn btn-primary px-4">Registreren</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>