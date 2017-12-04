<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 11/22/2017
 * Time: 13:39
 */

//titel, datum, begintijd, eindtijd, onderwerp, omschrijving leerlingnummer
//vervoer min_leerlingen max_leerlingen locatie lokaalnummer soort contactnummer account_id
//Get the id that's been given from bekijken.php
$id = ($_GET['evenement_id']);

//load info from database using the id
$db = db();
$stmt = $db->prepare("
SELECT e.id, e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id 
FROM evenement e 
JOIN evenement_soort es ON evenement_soort_id = es.id 
LEFT JOIN locatie l ON l.id = e.locatie_id 
LEFT JOIN adres a ON a.id = e.adres_id
WHERE e.id = $id");
$stmt->execute();

//put the info in $row
$row = $stmt->fetch();

//get the info out of $row into other changeble variables
$titel = $row["titel"];
$onderwerp = $row["onderwerp"];
$datum = $row["datum"];

if ($row["adres_id"] != "") {
    $adres = $row["adres_id"];
} else {
    $adres = "n.v.t.";
}

//check if the user has a certain user_id (admin or the corresponding builder_id)
if (0 == 'a') {
    $wijzigknop = '<a href="' . route('/index.php?evenementen=wijzigen&evenement_id=' . $id) . '"><i class="fa fa-pencil fa-3x" aria-hidden="true"></i></a>';
}
else {
    $wijzigknop = '';
}

if ($row["locatie_id"] != "") {
    $locatie = $row["locatie_id"];
} else {
    $locatie = "n.v.t.";
}
?>

<?= "$wijzigknop" ?><h2><?= "$titel" ?></h2>
<table>
    <tr>
        <td>Onderwerp:</td>
        <td><?= "$onderwerp" ?></td>
    </tr>
    <tr>
        <td>Datum: </td>
        <td><?= "$datum" ?></td>
    </tr>
    <tr>
        <td>Adres: </td>
        <td><?= "$adres" ?></td>
    </tr>
    <tr>
        <td>Locatie: </td>
        <td><?= "$locatie" ?></td>
    </tr>
</table>