<?php

include_once '../config.php';

$db = db();
$stmt = $db->prepare('SELECT * FROM evenement e WHERE  e.status = 1 AND e.eindtijd > ?');
$stmt->execute(array( date("Y-m-d H:i:s") ));
$evenementen = $stmt->fetchAll();


$user = get_user_info();

if( isset($_POST[ 'submit' ]) )
{
    $stmt = $db->prepare('SELECT * FROM inschrijving WHERE gewhitelist = ? and evenement_id = ? and leerlingnummer = ?');
    $stmt->execute(array( 1, $evenement[ 'evenement_id' ], $user[ 'leerlingnummer' ] ));
    $inschrijving = $stmt->fetch();

    if( $inschrijving !== false )
    {
        if( empty($inschrijving[ 'aangemeld_op' ]) )
        {
            $stmt = $db->prepare('UPDATE inschrijving SET aangemeld_op = ? WHERE evenement_id = ? and leerlingnummer = ?');
            $stmt->execute(array( date("Y-m-d H:i:s"), $evenement[ 'evenement_id' ], $user[ 'leerlingnummer' ] ));

            $receiver = $user[ 'leerlingnummer' ] . '@edu.rocmn.nl';
            $subject = 'Bevestiging inschrijving' . $evenement[ 'onderwerp' ];

            //QUERY voor ophalen gegevens voor de mail
            $db = db();
            $stmt = $db->prepare('select * from evenement WHERE evenement_id = :evenement_id');
            $stmt->bindParam('evenement_id', $evenement[ 'evenement_id' ], PDO::PARAM_INT);
            $stmt->execute();
            $evenement = $stmt->fetch();
            $leerling = $user;
            // toestemming $message
            include_once '../mail/bevestiging_inschrijving.php';
            /** @var $message */
            sendMail($receiver, $subject, $message);
            $inschrijving[ 'aangemeld_op' ] = 'X';

            success('Je hebt je ingeschreven!');
        }
        else
        {
            success('Je hebt je uitgeschreven!');
            $stmt = $db->prepare('UPDATE inschrijving SET aangemeld_op = ? WHERE evenement_id = ? and leerlingnummer = ?');
            $stmt->execute(array( null, $evenement[ 'evenement_id' ], $user[ 'leerlingnummer' ] ));
            $inschrijving[ 'aangemeld_op' ] = null;

            //test
            $receiver = $user[ 'leerlingnummer' ] . '@edu.rocmn.nl';
            $subject = 'Bevestiging inschrijving' . $evenement[ 'onderwerp' ];

            //QUERY voor ophalen gegevens voor de mail
            $db = db();
            $stmt = $db->prepare('select * from evenement WHERE evenement_id = :evenement_id');
            $stmt->bindParam('evenement_id', $evenement[ 'evenement_id' ], PDO::PARAM_INT);
            $stmt->execute();
            $evenement = $stmt->fetch();
            $leerling = $user;
            // toestemming $message
            include_once '../mail/bevestiging_uitschrijving.php';
            /** @var $message */
            sendMail($receiver, $subject, $message);

        }
    } else {
        $bericht = 'Je hebt geen recht om jezelf in te schrijven voor dit evenement';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="../../favicon.ico">-->
    <title>Studenten evenementen overzicht</title>
    <!-- Bootstrap core CSS -->
    <link href="<?= route('/public/css/style.css'); ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= route('/public/css/student.css'); ?>" rel="stylesheet">
</head>
<body>
<div class="collapse bg-inverse" id="navbarHeader">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 py-4">
                <h4 class="text-white">Contact</h4>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Follow on Twitter</a></li>
                    <li><a href="#" class="text-white">Like on Facebook</a></li>
                    <li><a href="#" class="text-white">Email me</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="navbar navbar-inverse bg-inverse">
    <div class="container d-flex justify-content-between">
        <a href="#" class="navbar-brand">Roc midden Nederland</a>
    </div>
    <div>
        <a class="dropdown-item" href="<?= route('/logout.php') ?>"><i class="fa fa-lock"></i> Logout</a>
        <?php

        $leerlingQuery = $db->prepare('SELECT * FROM account WHERE gebruikersnaam = :gebruikersnaam');
        $leerlingQuery->bindParam('gebruikersnaam', $gebruikersnaam);
        $leerlingQuery->execute();
        $leerlingen = $leerlingQuery->fetch();
        ?>
        <a class="dropdown-item"
           href="<?= route('/student/wijzigen_wachtwoord.php' . $leerlingen[ 'gebruikersnaam' ]) ?>"><i
                    class="fa fa-lock"></i> Wachtwoord wijzigen</a>
    </div>
</div>
<section class="jumbotron text-center img-responsive"
         style="background-image: url('<?= route("/public/img/logo.png"); ?>'); background-repeat: no-repeat; background-size: cover">
    <div class="container">
        <h1 class="jumbotron-heading">ROC midden Nederland</h1>
        <p class="lead text-muted">Evenementen overzicht van <?= $user[ 'roepnaam' ]; ?></p>
    </div>
</section>
<div class="album text-muted">
    <div class="container">
        <?php

        if(isset($bericht)) {
            echo $bericht;
        }
        foreach ($evenementen as $evenement)
        {

            $stmt = $db->prepare('SELECT * FROM inschrijving WHERE gewhitelist = ? and evenement_id = ? and leerlingnummer = ?');
            $stmt->execute(array( 1, $evenement[ 'evenement_id' ], $user[ 'leerlingnummer' ] ));
            $inschrijving = $stmt->fetch();
            // controleren of een student zichzelf wel mag inschrijven, hij moet gewhitelist zijn.
            // dat doen we door te controleren of de query uberhaupt een resultaat geeft omdat we queryen op gewhitelist ja / nee
            // uiteraard moet er ook gecontroleerd worden of er evenemeten bestaan in de toekomst.
            if( count($evenementen) === 0 || $inschrijving === false )
            {
                echo 'Er zijn geen evenementen beschikbaar op dit moment!';
            }
            else
            {

                ?>
                <div class="row">
                    <div class="card col-12">
                        <div class="card-body">
                            <h4 class="card-title"><?= ucfirst($evenement[ 'titel' ]); ?></h4>
                            <h5 class="text-muted"><?= ucfirst($evenement[ 'onderwerp' ]); ?></h5>
                            <p class="card-text"><?= ucfirst($evenement[ 'omschrijving' ]); ?></p>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    startdatum: <?= date('Y-M-d H:i', strtotime($evenement[ 'begintijd' ])); ?></li>
                                <li class="list-group-item">
                                    einddatum: <?= date('Y-M-d H:i', strtotime($evenement[ 'eindtijd' ])); ?></li>
                            </ul>
                        </div>
                        <form action="<?= route('/student/index.php'); ?>" method="post">
                            <input type="hidden" name="evenement_id" value="<?= $evenement[ 'evenement_id' ] ?>">
                            <button id="submit" type="submit" name="submit" class="btn btn-block btn-primary mb-3">
                                <?php

                                if( !empty($inschrijving[ 'aangemeld_op' ]) )
                                {
                                    print('Uitschrijven');
                                }
                                else
                                {
                                    print('Inschrijven');
                                }
                                ?></button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
        integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
        crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="https://v4-alpha.getbootstrap.com/assets/js/vendor/holder.min.js"></script>
<script>
    $(function () {
        Holder.addTheme("thumb", {background: "#55595c", foreground: "#eceeef", text: "Thumbnail"});
    });
</script>
<script src="https://v4-alpha.getbootstrap.com/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="https://v4-alpha.getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
