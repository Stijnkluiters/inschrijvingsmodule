<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 11/22/2017
 * Time: 13:39
 */

//
//Get the id that's been given from bekijken.php
$id = ($_GET['evenement_id']);

//load info from database using the id
$db = db();
$stmt = $db->prepare("
SELECT e.evenement_id, titel, e.begintijd, TIME(e.begintijd) starttijd, e.eindtijd, TIME(eindtijd) latertijd, e.onderwerp, e.omschrijving, e.vervoer, 
e.min_leerlingen, e.max_leerlingen, COUNT(i.leerlingnummer) aantal_inschrijvingen, e.locatie, e.lokaalnummer, s.soort, e.contactnr, e.account_id, e.status
FROM evenement e
JOIN soort s ON e.soort_id = s.soort_id
LEFT JOIN inschrijving i ON e.evenement_id = i.evenement_id
WHERE e.evenement_id = :evenement_id");
$stmt->bindParam('evenement_id', $id);
$stmt->execute();

//put the info in $row
$row = $stmt->fetch();

/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */
if($rol === 'externbedrijf') {
    viewEvent($row);
}

//get the info out of $row into variables
$titel = $row["titel"];
$onderwerp = $row["onderwerp"];
$begindatum = $row["begintijd"];

$begintijd = $row["starttijd"];
$begintijd = strtotime($begintijd);

$eindtijd = $row["latertijd"];
$eindtijd = strtotime($eindtijd);

$omschrijving = $row["omschrijving"];

$soort = $row["soort"];
if (strlen($soort) > 25) {
    $soort = substr($soort, 0, 26) . "...";
} else {
    $soort = $soort;
}
$vervoer = $row["vervoer"];
$contactnr = $row["contactnr"];

if (!empty($row['eindtijd'])) {
    $einddatum = $row['eindtijd'];
} else {
    $einddatum = 'n.v.t.';
}

if ($row["locatie"] != "") {
    $adres = $row["locatie"];
} else {
    $adres = "n.v.t.";
}
//*check if the user has a certain user_id (admin or the corresponding builder_id)
if (0 == 'a') {
    $wijzigknop = '<a href="' . route('/index.php?evenementen=wijzigen&evenement_id=' . $id) . '"class="pull-right control-group btn btn-primary">Evenement wijzigen</a>';
} else {
    $wijzigknop = '';
}

if ($row["lokaalnummer"] != "") {
    $lokaal = $row["lokaalnummer"];
} else {
    $lokaal = "n.v.t.";
}

//actief of niet
$activatie = $row['status'];

$activatieknop = '';
if ($activatie == 1) {
    $activatiemessage = "<span class='text-center bg-success'>Actief</span>";
    if($rol === 'beheerder') {
    $activatieknop = '<div><a href="' . route('/index.php?evenementen=activatie&evenement_id=' . $id) . '" class="pull-right btn btn-danger">Deactiveren</a></div>';
    }
} elseif ($activatie == 0) {
    $activatiemessage = "<span class='text-center bg-danger'>Inactief</span>";
    if($rol === 'beheerder') {
    $activatieknop = '<div><a href="' . route('/index.php?evenementen=activatie&evenement_id=' . $id) . '" class="pull-right btn btn-success">Activeren</a></div>';
    }
}

//progressbar berekeningen
$max = $row['max_leerlingen'];
$min = $row['min_leerlingen'];
$current = $row['aantal_inschrijvingen'];

$beschikbaar = $max - $current;

if ($max > 0 && $max > $min) {
    $inschrijvingspercentage = $current / $max * 100;
    if ($current == 0) {
        $inschrijvingspercentage = 2;
        $currentbar = $max * 0.02;
    } else {
        $currentbar = $current;
    }
    $percentmax = $max * 0.9;

    if ($current < $min || $current >= $percentmax) {
        $barcolor = 'warning';
    } else {
        $barcolor = 'success';
    }
    if ($current >= $max) {
        $barcolor = 'danger';
    }

    $bar = "<div class='progress' style='height: 20px'><div class='progress-bar bg-$barcolor' role='progressbar' style='width: $inschrijvingspercentage%' aria-valuenow='$currentbar' aria-valuemin='0' aria-valuemax='$max'></div></div> ";
    if ($beschikbaar == 0) {
        $beschikbaar = ', er zijn geen plekken beschikbaar!';
    } elseif ($beschikbaar == 1) {
        $beschikbaar = ', er is nog één plek beschikbaar!';
    } else {
        $beschikbaar = ", er zijn nog $beschikbaar plekken beschikbaar!";
    }
} else {
    $bar = '';
    $beschikbaar = '';
}

if ($current == 0) {
    $inschrijvingen = 'Er zijn nog geen inschrijvingen';
} elseif ($current == 1) {
    $inschrijvingen = '<a href="' . route('/index.php?inschrijving=overzicht&evenement_id=' . $id) . '">Er is 1 inschrijving</a>';

} else {
    $inschrijvingen = '<a href="' . route('/index.php?inschrijving=overzicht&evenement_id=' . $id) . '">Er zijn ' . $current . ' inschrijvingen</a>';
}
?>
<div class="card">
    <h4 class="card-header">
        <?= $titel ?>
        <div class='pull-right control-group'>
            <a href="<?= route('/index.php?evenementen=alles') ?>" class="btn btn-primary">Terug naar evenementen</a>
        </div>
    </h4>
    <div class="card-body">
        <h4 class="card-title"><?= $onderwerp ?></h4>
        <p class="card-text"><?= $omschrijving ?></p>
        <?= "$wijzigknop" ?>
    </div>
</div>

<div class="card">
    <h4 class="card-header">Inschrijvingen</h4>
    <div class="card-body">
        <p class="card-text"><?= $inschrijvingen, $beschikbaar ?></p>
        <div>
            <?= $bar ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-4">
        <div class="card">
            <h4 class="card-header">Waar en Wanneer</h4>
            <div class="card-body">
                <div class="card-text">
                    <table>
                        <tr>
                            <td>Begindatum:</td>
                            <td><?= date('d-M-Y', strtotime($begindatum)) ?></td>
                        </tr>
                        <tr>
                            <td>Einddatum:</td>
                            <td><?= date('d-M-Y', strtotime($einddatum)) ?></td>
                        </tr>
                        <tr>
                            <td>Adres:</td>
                            <td><?= "$adres" ?></td>
                        </tr>
                        <tr>
                            <td>Lokaal:</td>
                            <td><?= "$lokaal" ?></td>
                        </tr>
                        <tr>
                            <td>Tijd:</td>
                            <td><?= date('H:i', $begintijd) ?> - <?= date('H:i', $eindtijd) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <h4 class="card-header">Overig</h4>
            <div class="card-body">
                <table>
                    <tr>
                        <td>Soort:</td>
                        <td><?=$soort ?></td>
                    </tr>
                    <tr>
                        <td>Vervoer:</td>
                        <td><?=$vervoer ?></td>
                    </tr>
                    <tr>
                        <td>Contactnummer:</td>
                        <td><?=$contactnr ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <h4 class=" card-header">Actief?</h4>
            <div class="card-body">
                <h5>Op dit moment: <?= $activatiemessage ?></h5><?= $activatieknop ?>
            </div>
        </div>
    </div>
</div>


