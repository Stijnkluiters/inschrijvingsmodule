<?php
$db = db();

$stmt = $db->prepare("SELECT e.id, e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id 
FROM evenement e JOIN evenement_soort es 
ON evenement_soort_id = es.id 
LEFT JOIN locatie l ON l.id = e.locatie_id 
LEFT JOIN adres a ON a.id = e.adres_id");
$stmt->execute();
?>
<div class="allEvents">
    <?php
    while ($row = $stmt->fetch()) {
        $id = $row["id"];
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
    print("<div class='event'><a href='index.php?evenementen=wijzigen&evenement_id='$id'><i class=\"fa fa-pencil fa-2x\" aria-hidden=\"true\"></i></a><h4>$titel</h4>
<table>
    <tr>
        <td>Onderwerp: </td>
        <td>$onderwerp</td>
    </tr>
    <tr>
        <td>Datum: </td>
        <td>$datum</td>
    </tr>
    <tr>
        <td>Adres: </td>
        <td>$adres</td>
    </tr>
    <tr>
        <td>Locatie: </td>
        <td>$locatie</td>
    </tr>
</table>
</div>");}
    ?>
</div>
