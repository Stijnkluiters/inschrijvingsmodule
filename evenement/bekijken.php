<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
$db = db();

$stmt = $db->prepare("SELECT e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id FROM evenement e JOIN evenement_soort es ON evenement_soort_id = es.id");

$stmt->execute();
$results = $stmt->fetchAll();

?>
<table>
    <?php
    foreach ($results as $result) {
        var_dump($result);
    }
    ?>
</table>
</body>
</html>