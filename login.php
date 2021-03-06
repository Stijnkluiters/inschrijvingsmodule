<?php

/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 16-11-2017
 * Time: 13:29
 */
include 'config.php';
// laat een bericht onderaan in de javascript zien.
if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    //Laat het bericht maar 1x zien per request!
    unset($_SESSION['message']);
}
if( isset($_POST[ 'submit' ]) )
{

    //secret recaptcha key:
    $secret_key = '6LcffjsUAAAAAMqC6IpdiCP7x1nWmJB-CDGnEia3';
    $response = post_request('https://www.google.com/recaptcha/api/siteverify', [
        'secret'   => $secret_key,
        'response' => $_POST[ 'g-recaptcha-response' ]
    ]);
    $response = json_decode($response, true);

    // controleren of google een status: success geeft.
    if( $response['success'] )
    {
        $username = $_POST[ 'gebruikersnaam' ];
        $password = $_POST[ 'wachtwoord' ];

        if( empty($username) )
        {
            $error = 'gebruikersnaam is verplicht.';
        }
        if( strlen($username) < 5 )
        {
            $error = 'gebruikersnaam moet minimaal 5 karakters zijn';
        }
        // <script></script>
        $username = filter_input(INPUT_POST, 'gebruikersnaam', FILTER_SANITIZE_STRING);

        if( strlen($password) < 8 )
        {
            $error = 'wachtwoord moet langer dan 7 karakters zijn';
        }
        $password = filter_input(INPUT_POST, 'wachtwoord', FILTER_SANITIZE_STRING);

        if( !isset($error) )
        {
            $output = login($username, $password);
            if( $output === 'NOUSER' || $output === 'INVALIDPASSWORD' )
            {
                $error = 'incorrecte gegevens, probeer het opnieuw.';
            }
            elseif( $output === 'USERDELETED' )
            {
                $error = 'Account is geblokkeerd';
            }
            else
            {
                if( !isset($error) )
                {
                    //alle inloggegevens kloppen, gebruiker is nu ingelogd. tijd om door te sturen naar het dashboard.
                    $role = get_account_his_role($_SESSION[ authenticationSessionName ]);
                    if( $role[ 'rolnaam' ] === 'leerling' )
                    {
                        redirect('/student/index.php', 'Welkom!');
                    }
                    else
                    {
                        redirect('/index.php', 'Welkom!');
                    }
                }

            }

        }

    }
    else
    {

        if( in_array('missing-input-response', $response[ 'error-codes' ]) )
        {
            $error = 'Recaptcha is verplicht';
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
                                <input type="text" class="form-control"
                                       placeholder="Gebruikersnaam"
                                       value="<?= (isset($username)) ? $username : ''; ?>"
                                       name="gebruikersnaam"
                                       required="required"/>
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" class="form-control" placeholder="Wachtwoord" name="wachtwoord"

                                       autocomplete="off"
                                       required="required"/>
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
                        <div class="g-recaptcha" data-sitekey="6LcffjsUAAAAAK_qsbG5FQm4UnceLL2O5ztC0Kp7"></div>
                    </form>
                </div>

                <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                    <div class="card-body text-center">
                        <div>
                            <h2>Het ROC midden Nederland</h2>
                            <p>Inschrijvingsmodule </p>
<!--                            <a href="--><?//= route('/register.php'); ?><!--" type="button" class="btn btn-primary px-4">Registreren</a>-->
                        </div>
                    </div>
                </div>
            </div>
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
    $.notify("<?= $message; ?>", {
        className: 'info'
    });
    <?php } ?>


</script>
</html>