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

if (isset($_POST["aanmelden"])) {
    $aanmelderror = [];
    $aanmeldUpdate = filter_input(INPUT_POST, 'aanmelden', FILTER_SANITIZE_NUMBER_INT);
    $leerlingnummer = filter_input(INPUT_POST, 'leerlingnummer', FILTER_SANITIZE_NUMBER_INT);
    if ($aanmeldUpdate == '0' || $aanmeldUpdate === '1') {
        if ($aanmeldUpdate === '1') {
            $aanmeldUpdate = date("Y-m-d H:i:s");
        } elseif ($aanmeldUpdate === '0') {
            $aanmeldUpdate = null;
        }

        $aanmeldStmt = $db->prepare("UPDATE inschrijving SET aangemeld_op = ? WHERE evenement_id = ? AND leerlingnummer =?");
        $aanmeldStmt->execute(array($aanmeldUpdate, $evenement_id, $leerlingnummer));
        $aanmeldstatus = ($aanmeldUpdate == 0) ? ' Afgemeld ' : ' Aangemeld ';
        redirect('/index.php?inschrijving=overzicht&evenement_id=' . $evenement_id, 'Leerlingnummer: ' . $leerlingnummer . ' ' . $aanmeldstatus . '.');
    }
}

if (isset($_POST["whitelist"])) {
    $leerlingnummer = filter_input(INPUT_POST, 'leerlingnummer', FILTER_SANITIZE_NUMBER_INT);
    $updatewhitelist = filter_input(INPUT_POST, 'whitelist', FILTER_SANITIZE_NUMBER_INT);
    if ($updatewhitelist == '0' || $updatewhitelist == '1') {
        chainEventToLeerling(intval($evenement_id), intval($leerlingnummer), intval($updatewhitelist));
        $status = ($updatewhitelist == 0) ? ' geblacklist ' : ' gewhitelist ';
        redirect('/index.php?inschrijving=overzicht&evenement_id=' . $evenement_id, 'Leerlingnummer: ' . $leerlingnummer . ' ' . $status . '.');

    }
}

if (isset($_POST["toestemming"])) {
    $value = $_POST["toestemming"];
    $leerlingnummer = filter_input(INPUT_POST, 'leerlingnummer', FILTER_SANITIZE_NUMBER_INT);
    $toestemming = 0;

    // evenementen ophalen afhankelijk van de primary key, evenement_id
    $stmt = $db->prepare('select * from evenement WHERE evenement_id = ?');
    $stmt->execute(array($evenement_id));
    $evenement = $stmt->fetch();

    if (empty($evenement)) {
        $error = 'Evenement bestaat niet?';
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
        include_once 'afwijzingsmessage.php';

    }

    $r = chainEventToLeerling(intval($evenement_id), intval($leerlingnummer), $toestemming);
    $toestemmingStmt = $db->prepare("UPDATE inschrijving SET toestemming = ? WHERE evenement_id = ? AND leerlingnummer = ?");
    $toestemmingStmt->execute(array($toestemming, $evenement_id, $leerlingnummer));
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

                $aanmelden = $inschrijving['aangemeld_op'];
                //if ($knopExists != false) {
                    $aanmeldknop = 'nog niet gewhitelist';
                    if (($rol === 'beheerder' || $rol === 'docent') && $inschrijving['gewhitelist'] == 1) {
                        if ($aanmelden == '') {
                            $aanmeldknop = '<button class="btn btn-success pull-right" name="aanmelden" value="1">Aanmelden</button>';
                        } else {
                            $aanmeldknop = '<button class="btn btn-danger pull-right" name="aanmelden" value="0">Afmelden</button>';
                        }
                    }

                    $whitelistknop = '';
                    if ($inschrijving['gewhitelist'] === '1') {
                        if ($rol === 'beheerder') {
                            $whitelistknop = '<button class="btn btn-danger pull-right" name="whitelist" value="0">
                            <i class="fa fa-times" aria-hidden="true">
                            </i> Blacklisten
                            </button>';
                        }
                        $whitelist = "<input type='hidden' name='leerlingnummer' value='$leerlingnummer2'>
                        <span class='text-success'>
                        <i class='fa fa-check' aria-hidden='true'>
                        </i>
                        " . $whitelistknop . "
                        </span>";
                    } elseif ($inschrijving['gewhitelist'] === '0') {
                        if ($rol === 'beheerder') {
                            $whitelistknop = "<button 
                            class='btn btn-success pull-right' 
                            type='submit' 
                            name='whitelist' 
                            value='1'>
                            <i class='fa fa-check' aria-hidden='true'>
                            </i> Whitelisten</button>";
                        }
                        $whitelist = "<input type='hidden' name='leerlingnummer' value='$leerlingnummer2'>
                        <span class='text-danger'>
                        <i class='fa fa-times' aria-hidden='true'>
                        </i>$whitelistknop
                        </span>";
                    } else {
                        $whitelist = '';
                    }
                //} ?>

                <tr>
                    <form method="POST"
                          action='<?= route("/index.php?inschrijving=overzicht&evenement_id=" . $evenement_id); ?>'>
                        <input type="hidden" name="leerlingnummer" value="<?= $inschrijving['leerlingnummer']; ?>"/>

                        <td><?= $inschrijving['leerlingnummer'] ?></td>
                        <td><?= ucfirst($inschrijving['roepnaam']) .
                            " " .
                            $inschrijving['tussenvoegsel'] .
                            " " .
                            ucfirst($inschrijving['achternaam']); ?></td>
                        <td><?= $aanmeldknop, (!empty($inschrijving['aangemeld_op'])) ? date('Y-M-d H:i', strtotime($inschrijving['aangemeld_op'])) : '' ?></td>
                        <td><?= $whitelist ?></td>
                        <td>
                            <?php

                            if (!$inschrijving['toestemming']) {
                                if (!empty($inschrijving['aangemeld_op'])) {
                                    if ($rol === 'beheerder') {
                                        ?>

                                        <button type="submit" name="toestemming" value="ja"
                                                class="pull-right btn btn-success">
                                            Toestemming verlenen
                                        </button>
                                    <?php } ?>
                                    <i class="text-danger fa fa-times" aria-hidden="true"></i>
                                <?php } else {
                                    ?>Nog niet aangemeld<?php
                                }
                            } else {
                                if ($rol === 'beheerder') {
                                    ?>

                                    <button type="submit" name="toestemming" value="nee"
                                            class="pull-right btn btn-danger">
                                        Toestemming intrekken/weigeren
                                    </button><?php } ?><i class="text-success fa fa-check" aria-hidden="true"></i>
                                <?php if ($rol === 'beheerder') { ?>
                                    <div class="form-group">
                                        <label for="comment">Opmerking afwijzing</label>
                                        <textarea class="form-control" id="comment"></textarea>
                                    </div>
                                <?php  }
                            } ?>
                        </td>
                    </form>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </div>
</div>
