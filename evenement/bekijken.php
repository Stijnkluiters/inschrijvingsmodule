<?php
$db = db();

$stmt = $db->prepare("
SELECT e.id, e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id 
FROM evenement e JOIN evenement_soort es 
ON evenement_soort_id = es.id 
LEFT JOIN locatie l ON l.id = e.locatie_id 
LEFT JOIN adres a ON a.id = e.adres_id");
$stmt->execute();
?>
<div class="allEvents">
    <table>
        <tr>
            <th id="titel" class="tablehead">naam</th>
            <th class="tablehead">onderwerp</th>
            <th class="tablehead">datum</th>
            <th class="tablehead">plaats</th>
            <th class="tablehead">locatie</th>
        </tr>
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

    //<a href='index.php?evenementen=specifiek&evenement_id=" . $id . "'><i class="fa fa-pencil fa-2x" aria-hidden="true"></i></a>
    print("
        <tr>
            <td><a href='index.php?evenementen=specifiek&evenement_id=" . $id . "'>$titel</a></td>
            <td>$onderwerp</td>
            <td>$datum</td>
            <td>$adres</td>
            <td>$locatie</td>
        </tr>
");}
    ?>
    </table>
</div>
