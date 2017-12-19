<?php

include_once '../config.php';

$db = db();
$stmt = $db->prepare('SELECT * FROM evenement e JOIN inschrijving i ON e.evenement_id = i.evenement_id
WHERE i.gewhitelist = 1 AND e.status = 1 AND e.eindtijd > ?');
$stmt->execute(array(date("Y-m-d H:i:s")));
$evenemten = $stmt->fetchAll();

if(count($evenemten)===0) {
    $bericht = "Er zijn geen evenementen beschikbaar op dit moment!";
}

$user = get_user_info();
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
        $db = db();
        $leerlingQuery = $db->prepare('SELECT * FROM account WHERE gebruikersnaam = :gebruikersnaam');
        $leerlingQuery->bindParam('gebruikersnaam', $gebruikersnaam);
        $leerlingQuery->execute();
        $leerlingen = $leerlingQuery->fetch();
        ?>
        <a class="dropdown-item" href="<?= route('/student/wijzigen_wachtwoord.php' . $leerlingen['gebruikersnaam']) ?>"><i class="fa fa-lock"></i> Wachtwoord wijzigen</a>
    </div>
</div>
<section class="jumbotron text-center img-responsive" style="background-image: url('<?= route("/public/img/logo.png"); ?>'); background-repeat: no-repeat; background-size: cover">
    <div class="container">
        <h1 class="jumbotron-heading">ROC midden Nederland</h1>
        <p class="lead text-muted">Evenementen overzicht van <?= $user['roepnaam'] ; ?></p>
    </div>
</section>
<div class="album text-muted">
    <div class="container">
        <?php
        if(isset($bericht)){
            print($bericht);
        }
        ?>
        <?php foreach ($evenemten as $evenemnt) { ?>
        <div class="row">
            <div class="card col-12">
                <div class="card-body">
                    <h4 class="card-title"><?= ucfirst($evenemnt[ 'titel' ]); ?></h4>
                    <h5 class="text-muted"><?= ucfirst($evenemnt[ 'onderwerp' ]); ?></h5>
                <p class="card-text"><?= ucfirst($evenemnt[ 'omschrijving' ]); ?></p>
                <ul class="list-group">
                    <li class="list-group-item">startdatum: <?= date('Y-M-d H:i', strtotime($evenemnt[ 'begintijd' ])); ?></li>
                    <li class="list-group-item">einddatum: <?= date('Y-M-d H:i', strtotime($evenemnt[ 'eindtijd' ])); ?></li>
                </ul>
            </div>
                <form action="<?= route('/student/index.php') ?>" method="post">
                    <?php
                    $user = get_user_info();
                    $stmt = $db->prepare('SELECT * FROM inschrijving WHERE gewhitelist = ? and evenement_id = ? and leerlingnummer = ?');
                    $stmt->execute(array(1, $evenemnt['evenement_id'],$user['leerlingnummer']));
                    $inschrijving = $stmt->fetch();
                    if(isset($_POST['submit'])){
                        if(empty($inschrijving['aangemeld_op'])){
                            $stmt = $db->prepare('UPDATE inschrijving SET aangemeld_op = ? WHERE evenement_id = ? and leerlingnummer = ?');
                            $stmt->execute(array(date("Y-m-d H:i:s"), $evenemnt['evenement_id'], $user['leerlingnummer']));

                            $receiver = $user['leerlingnummer'].'@edu.rocmn.nl';
                            $subject =  'Bevestiging inschrijving' . $evenemnt['onderwerp'];

                            // toestemming $message
                            $message = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head><title></title>  <!--[if !mso]><!-- -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">  #outlook a {
        padding: 0;
    }

    .ReadMsgBody {
        width: 100%;
    }

    .ExternalClass {
        width: 100%;
    }

    .ExternalClass * {
        line-height: 100%;
    }

    body {
        margin: 0;
        padding: 0;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
    }

    table, td {
        border-collapse: collapse;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
    }

    p {
        display: block;
        margin: 13px 0;
    }</style><!--[if !mso]><!-->
    <style type="text/css">  @media only screen and (max-width: 480px) {
        @-ms-viewport {
            width: 320px;
        }    @viewport {
            width: 320px;
        }
    }</style><!--<![endif]--><!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml><![endif]--><!--[if lte mso 11]>
    <style type="text/css">  .outlook-group-fix {
        width: 100% !important;
    }</style><![endif]--><!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
    <style type="text/css">        @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);    </style>
    <!--<![endif]-->
    <style type="text/css">  @media only screen and (min-width: 480px) {
        .mj-column-per-40 {
            width: 40% !important;
        }

        .mj-column-per-60 {
            width: 60% !important;
        }

        .mj-column-per-100 {
            width: 100% !important;
        }
    }</style>
</head>
<body style="background: #FFFFFF;">
<div class="mj-container" style="background-color:#FFFFFF;"><!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center"
           style="width:600px;">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]-->
    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0">
        <tbody>
        <tr>
            <td>
                <div style="margin:0px auto;max-width:600px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;"
                           align="center" border="0">
                        <tbody>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;">
                                <!--[if mso | IE]>
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="vertical-align:top;width:240px;">      <![endif]-->
                                <div class="mj-column-per-40 outlook-group-fix"
                                     style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="word-wrap:break-word;font-size:0px;padding:33px 33px 33px 33px;"
                                                align="center">
                                                <table role="presentation" cellpadding="0" cellspacing="0"
                                                       style="border-collapse:collapse;border-spacing:0px;"
                                                       align="center" border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width:174px;"><img alt="Roc midden Nederland"
                                                                                      title="" height="auto"
                                                                                      src="https://topolio.s3-eu-west-1.amazonaws.com/uploads/5a2e5baa87c70/1512987615.jpg"
                                                                                      style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;"
                                                                                      width="174"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--[if mso | IE]>      </td>
                            <td style="vertical-align:top;width:360px;">      <![endif]-->
                                <div class="mj-column-per-60 outlook-group-fix"
                                     style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;"
                                                align="left">
                                                <div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:left;">
                                                    <p>Beste student,<br><br>Hierbij de bevestiging van de inschrijving
                                                        voor het evenement<br>&#xA0;<br>Op '.date('d-M-Y H:i',strtotime($inschrijving['created_at'])).' ben je op
                                                        <strong>ingeschreven voor</strong>:</p>
                                                    <table align="center" border="2" cellpadding="1" cellspacing="2"
                                                           style="width:100%;"
                                                           summary="Het ongedaan maken van je inschrijving kan op de website waar je je hebt ingeschreven voor het evenement">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">Inschrijvingsnummer voor het evenement</th>
                                                            <th scope="col">'.md5($leerlingnummer . $evenement_id).'</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><strong>uitvoerperiode</strong></td>
                                                            <td>Van: '. date('d-M-Y H:i',strtotime($evenement['starttijd'])) . ' tot: ' . date("d-M-Y H:i",strtotime($evenement['eindtijd'])) . '</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Titel</strong></td>
                                                            <td>'.ucfirst($evenement['titel']).'</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Omschrijving</b></td>
                                                            <td>'.ucfirst($evenement['omschrijving']).'</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <p></p>
                                                    <p><strong>Uw verzoek is inbehandeling, wacht op bevestiging.</strong></p>
                                                    <p></p>
                                                    <p>Met vriendelijke groet,</p>
                                                    <p><br>ROC midden Nederland</p></div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--[if mso | IE]>      </td></tr></table>      <![endif]--></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center"
           style="width:600px;">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]-->
    <div style="margin:0px auto;max-width:600px;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center"
               border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;">
                    <!--[if mso | IE]>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="vertical-align:top;width:600px;">      <![endif]-->
                    <div class="mj-column-per-100 outlook-group-fix"
                         style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody></tbody>
                        </table>
                    </div>
                    <!--[if mso | IE]>      </td></tr></table>      <![endif]--></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--[if mso | IE]>      </td></tr></table>      <![endif]--></div>
</body>
</html>';
                            sendMail($receiver,$subject,$message);
                            $inschrijving['aangemeld_op'] = 'X';

                            success('Je hebt je ingeschreven!');
                        }else{
                            success('Je hebt je uitgeschreven!');
                            $stmt = $db->prepare('UPDATE inschrijving SET aangemeld_op = ? WHERE evenement_id = ? and leerlingnummer = ?');
                            $stmt->execute(array(NULL, $evenemnt['evenement_id'], $user['leerlingnummer']));
                            $inschrijving['aangemeld_op'] = NULL;
                        }
                    }
                    ?>
                    <button id="submit" type="submit" name="submit" class="btn btn-block btn-primary mb-3">
                        <?php
                            if(!empty($inschrijving['aangemeld_op'])){
                                print('Uitschrijven');
                            }else{
                                print('Inschrijven');
                            }
                        ?></button>
                </form>
            </div>
            </div>
            <?php } ?>
    </div>
</div>
<footer class="text-muted">
    <div class="container">
        <p class="float-right">
            <a href="#">Back to top</a>
        </p>
        <p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
        <p>New to Bootstrap? <a href="../../">Visit the homepage</a> or read our <a href="../../getting-started/">getting
                started guide</a>.</p>
    </div>
</footer>

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
