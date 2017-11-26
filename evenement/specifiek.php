<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 11/22/2017
 * Time: 13:39
 */

$id = ($_GET['evenement_id']);

$db = db();

$stmt = $db->prepare("
SELECT e.id, e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id 
FROM evenement e JOIN evenement_soort es 
ON evenement_soort_id = es.id 
LEFT JOIN locatie l ON l.id = e.locatie_id 
LEFT JOIN adres a ON a.id = e.adres_id
WHERE e.id = $id");
$stmt->execute();

$row = $stmt->fetch();
$titel = $row["titel"];
$onderwerp = $row["onderwerp"];
$datum = $row["datum"];

if($row["adres_id"] != "") {
    $adres = $row["adres_id"];
}
else {
    $adres = "n.v.t.";
}

if($row["locatie_id"] != ""){
    $locatie = $row["locatie_id"];
}
else{
    $locatie = "n.v.t.";
}
print("
    <h2>$titel</h2>
    <table>
    <tr>
    <td></td>
    <td>$onderwerp</td>
    <p>$datum</p>
    <p>$adres</p>
    <p>$locatie</p>
    </table>");
?>