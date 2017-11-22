<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
$db = db();

$stmt = $db->prepare("SELECT e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id FROM evenement e JOIN evenement_soort es ON evenement_soort_id = es.id LEFT JOIN locatie l ON l.id = e.locatie_id LEFT JOIN adres a ON a.id = e.adres_id");
$stmt->execute();
?>
<div>
    <table>
    <?php

    while ($row = $stmt->fetch()) {
        $adres = "n.v.t.";
        $locatie = "n.v.t.";
        $titel = $row["titel"];
        $onderwerp = $row["onderwerp"];
        $datum = $row["datum"];
        if($row["adres_id"] != "") {
            $adres = $row["adres_id"];
        }
        if($row["locatie_id"] != ""){
            $locatie = $row["locatie_id"];
        }
    print("<tr><td>$titel</td><td> Onderwerp: $onderwerp</td><td> Datum: $datum</td><td> Adres: $adres</td><td> Locatie: $locatie</td></tr>");}
    ?>
    </table>
</div>
</body>
</html>