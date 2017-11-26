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


        //todo: what if there are no rows? let the user know;
        //todo: escape xss attack with htmlentities();
    print("
        <tr>
            <td><a href='" . route('/index.php?evenementen=wijzigen&evenement_id="'.$id.'"') . "'>$titel</a></td>
            <td>$onderwerp</td>
            <td>$datum</td>
            <td>$adres</td>
            <td>$locatie</td>
        </tr>
");}
    ?>
    </table>
</div>
