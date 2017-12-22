<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 13-12-2017
 * Time: 10:18
 */

$evenement_id = filter_var(filter_input(INPUT_GET, 'evenement_id', FILTER_SANITIZE_STRING), FILTER_VALIDATE_INT);
if (!filter_var($evenement_id, FILTER_VALIDATE_INT)) {
    redirect('/index.php?evenement=overzicht', 'Er is wat misgegaan met de url? are you trying to hack this?');
}
$db = db();
if (isset($_POST["whitelist"])) {
    $leerlingnummer = $_POST['leerlingnummer'];
    $updatewhitelist = filter_input(INPUT_POST, 'whitelist', FILTER_SANITIZE_NUMBER_INT);
    if ($updatewhitelist == '0' || $updatewhitelist == '1') {

        $upwhite = $db->prepare("UPDATE inschrijving SET gewhitelist =? WHERE evenement_id =? AND leerlingnummer=? ");
        $upwhite->execute(array(
            $updatewhitelist, $evenement_id, $leerlingnummer));
    }
}

if (isset($_POST["toestemming"])) {
    $value = $_POST["toestemming"];
    $leerlingnummer = $_POST["leerlingnummer"];
    $toestemming = 0;

    // evenementen ophalen afhankelijk van de primary key, evenement_id
    $stmt = $db->prepare('select * from evenement WHERE evenement_id = ?');
    $stmt->execute(array($evenement_id));
    $evenement = $stmt->fetch();

    if (empty($evenement)) {
        $error = 'Evenement bestaat niet? wtf?';
    }
    // leerlingen ophalen afhankelijk van de primary key, leerlingnummer
    $stmt = $db->prepare('select * from leerling WHERE leerlingnummer = ?');
    $stmt->execute(array($leerlingnummer));
    $leerling = $stmt->fetch();

    $stmt2 = $db->prepare("select created_at, aangemeld_op FROM inschrijving WHERE leerlingnummer = ? AND evenement_id =?");
    $stmt2->execute(array($leerlingnummer, $evenement_id));
    $inschrijving = $stmt2->fetch();

    $subject = 'asdf';

    if ($value == "ja") {
        $toestemming = 1;

        // onderwerp
        $subject = 'Bevestiging inschrijving';
        include_once 'inschrijvingsmessage.php';

    } elseif ($value == "nee") {
        // hier moet mnr. van dalen even de $message template aanmaken.
        $toestemming = 0;
        $subject = 'Afwijzing inschrijving';
        $message = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>  <title></title>  <!--[if !mso]><!-- -->  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!--<![endif]--><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><style type="text/css">  #outlook a { padding: 0; }  .ReadMsgBody { width: 100%; }  .ExternalClass { width: 100%; }  .ExternalClass * { line-height:100%; }  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }  p { display: block; margin: 13px 0; }</style><!--[if !mso]><!--><style type="text/css">  @media only screen and (max-width:480px) {    @-ms-viewport { width:320px; }    @viewport { width:320px; }  }</style><!--<![endif]--><!--[if mso]><xml>  <o:OfficeDocumentSettings>    <o:AllowPNG/>    <o:PixelsPerInch>96</o:PixelsPerInch>  </o:OfficeDocumentSettings></xml><![endif]--><!--[if lte mso 11]><style type="text/css">  .outlook-group-fix {    width:100% !important;  }</style><![endif]--><!--[if !mso]><!-->    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">    <style type="text/css">        @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);    </style>  <!--<![endif]--><style type="text/css">  @media only screen and (min-width:480px) {    .mj-column-per-40 { width:40%!important; }.mj-column-per-60 { width:60%!important; }.mj-column-per-100 { width:100%!important; }  }</style></head><body style="background: #FFFFFF;">    <div class="mj-container" style="background-color:#FFFFFF;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0"><tbody><tr><td><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:240px;">      <![endif]--><div class="mj-column-per-40 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:33px 33px 33px 33px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:174px;"><img alt="Roc midden Nederland" title="" height="auto" src="https://topolio.s3-eu-west-1.amazonaws.com/uploads/5a2e5baa87c70/1512987615.jpg" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="174"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>      </td><td style="vertical-align:top;width:360px;">      <![endif]--><div class="mj-column-per-60 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;" align="left"><div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:left;"><p>Beste {l.studentnaam},<br><br>Je aanvraag op {created_at} voor het evenement{titel}&#xA0;is <b>afgekeurd</b>,&#xA0; voor de rede van de afwijzing voor het evenement kan je naar de organisator van het evenement.&#xA0;<br>&#xA0;</p><p>Met vriendelijke groet,</p><p><br>ROC midden Nederland</p></div></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div></td></tr></tbody></table><!--[if mso | IE]>      </td></tr></table>      <![endif]-->      <!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">        <tr>          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">      <![endif]--><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;"><!--[if mso | IE]>      <table role="presentation" border="0" cellpadding="0" cellspacing="0">        <tr>          <td style="vertical-align:top;width:600px;">      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>      </td></tr></table>      <![endif]--></div></body></html>';

    }
    $stmt = $db->prepare("UPDATE inschrijving SET toestemming = ? WHERE leerlingnummer = ? AND evenement_id = ?");
    $r = $stmt->execute(array($toestemming, $leerlingnummer, $evenement_id));
    if ($r) {
        sendMail($leerlingnummer . '@edu.rocmn.nl', $subject, $message);
    } else {
        $error = 'Opslaan niet gelukt! probeer het opnieuw';
    }


}
$stmt = $db->prepare('select * from inschrijving i
  JOIN leerling l ON i.leerlingnummer = l.leerlingnummer
  WHERE i.evenement_id = :evenement_id');
$stmt->bindParam('evenement_id', $evenement_id, PDO::PARAM_INT);
$stmt->execute();
$inschrijvingen = $stmt->fetchAll();
?>
<div class="card">
    <div class="card-header">
        <div class='pull-right control-group'>
            <a href="<?= route('/index.php?evenementen=specifiek&evenement_id=' . $evenement_id) ?>"
               class="btn btn-primary">Terug naar evenement</a>
        </div>
        <h4>Inschrijvingen</h4>

    </div>
    <div class="card-body">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Leerlingnummer</th>
                    <th>Naam</th>
                    <th>Aangemeld op</th>
                    <th>Gewhitelist</th>
                    <th>Toestemming</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Leerlingnummer</th>
                    <th>Naam</th>
                    <th>Aangemeld op</th>
                    <th>Gewhitelist</th>
                    <th>Toestemming</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($inschrijvingen as $inschrijving) {

                    $leerlingnummer2 = $inschrijving['leerlingnummer'];
                    if ($inschrijving['gewhitelist'] === '1') {
                        $whitelist = '<input type="hidden" name="leerlingnummer" value="' . $leerlingnummer2 . '">
                <span class="text-success">
                <i class="fa fa-check" aria-hidden="true">
                </i>
                <button class="btn btn-danger pull-right" name="whitelist" value="0">
                <i class="fa fa-times" aria-hidden="true">
                </i> Blacklisten
                </button>
                </span>';
                    } elseif ($inschrijving['gewhitelist'] === '0') {
                        $whitelist = '<input type="hidden" name="leerlingnummer" value="' . $leerlingnummer2 . '">
                <span class="text-danger">
                <i class="fa fa-times" aria-hidden="true">
                </i>
                <button class="btn btn-success pull-right" type="submit" name="whitelist" value="1">
                <i class="fa fa-check" aria-hidden="true">
                </i> Whitelisten</button>
                </span>';
                    } else {
                        $whitelist = '';
                    } ?>

                    <tr>
                        <form method="POST"
                              action='<?= route("/index.php?inschrijving=overzicht&evenement_id=" . $evenement_id); ?>'>
                            <input type="hidden" name="leerlingnummer" value="<?= $inschrijving['leerlingnummer']; ?>"/>

                            <td><?= $inschrijving['leerlingnummer'] ?></td>
                            <td><?= ucfirst($inschrijving['roepnaam']) . " " . $inschrijving['tussenvoegsel'] . " " . ucfirst($inschrijving['achternaam']); ?></td>
                            <td><?= (!empty($inschrijving['aangemeld_op'])) ? date('Y-M-d H:i', strtotime($inschrijving['aangemeld_op'])) : '' ?></td>
                            <td><?= $whitelist ?></td>
                            <td>
                                <?php if (!$inschrijving['toestemming']) { ?>

                                    <button type="submit" name="toestemming" value="ja" class="pull-right btn btn-success">
                                        Toestemming verlenen
                                    </button>
                                    <i class="text-danger fa fa-times" aria-hidden="true"></i>
                                <?php } else { ?>
                                    <button type="submit" name="toestemming" value="nee" class="pull-right btn btn-danger">
                                        Toestemming intrekken/weigeren
                                    </button><i class="text-success fa fa-check" aria-hidden="true"></i>
                                    <div class="form-group">
                                        <label for="comment">Opmerking afwijzing</label>
                                        <textarea class="form-control" id="comment"></textarea>
                                    </div>
                                <?php } ?>
                            </td>
                    </tr>
                    </form>
                <?php } ?>
                </tbody>
            </table>

    </div>
</div>
