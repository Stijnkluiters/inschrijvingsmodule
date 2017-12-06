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
SELECT e.evenement_id, titel, e.begintijd, e.eindtijd, e.onderwerp, e.omschrijving, e.vervoer, 
e.min_leerlingen, e.max_leerlingen, COUNT(i.leerlingnummer) aantal_inschrijvingen, e.locatie, e.lokaalnummer, e.soort, e.contactnr, e.account_id
FROM evenement e
LEFT JOIN inschrijving i ON e.evenement_id = i.evenement_id
WHERE e.evenement_id = :evenement_id");
$stmt->bindParam('evenement_id',$id);
$stmt->execute();

//put the info in $row
$row = $stmt->fetch();

//get the info out of $row into variables
$titel = $row["titel"];
$onderwerp = $row["onderwerp"];
$begindatum = $row["begindatum"];
if($row['einddatum']){
    $einddatum = $row['einddatum'];
}
else{

}
if ($row["locatie"] != "") {
    $adres = $row["locatie"];
} else {
    $adres = "n.v.t.";
}
//*check if the user has a certain user_id (admin or the corresponding builder_id)
if (0 == 'a') {
    $wijzigknop = '<a href="' . route('/index.php?evenementen=wijzigen&evenement_id=' . $id) . '"><i id="wijzig" class="fa fa-pencil fa-3x" aria-hidden="true"></i></a>';
}
else {
    $wijzigknop = '';
}

if ($row["lokaalnummer"] != "") {
    $lokaal = $row["lokaalnummer"];
} else {
    $lokaal = "n.v.t.";
}
?>

<?= "$wijzigknop" ?><h2><?= "$titel" ?></h2>
<table>
    <tr>
        <td>Onderwerp:</td>
        <td><?= "$onderwerp" ?></td>
    </tr>
    <tr>
        <td>Begindatum: </td>
        <td><?= "$begindatum" ?></td>
    </tr>
    <tr>
        <td>Einddatum: </td>
        <td><?= "$einddatum" ?></td>
    </tr>
    <tr>
        <td>Adres: </td>
        <td><?= "$adres" ?></td>
    </tr>
    <tr>
        <td>Lokaal: </td>
        <td><?= "$lokaal" ?></td>
    </tr>
</table>