
<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 11/24/2017
 * Time: 15:44
 */

var_dump($_POST);
$db = db();
//Get the id that's been given from specifiek.php
$id = intval(filter_input(INPUT_GET,'evenement_id',FILTER_SANITIZE_STRING));
if (!filter_var($id, FILTER_VALIDATE_INT)){
    echo "evenement_id is geen numerieke waarde";
    exit;
}
if(isset($_POST['titel'])){
    $titel = $_POST['titel'];
    $update = true;
}
else{
    $titel_update = '';
    $update = false;
}

if($update = true){
    $stmt = $db->prepare('
            UPDATE evenement SET
            titel = :titel 
            WHERE id = :id');
    $stmt->bindParam('titel', $titel, PDO::PARAM_STR);
    $stmt->bindParam('id', $id);
    $stmt->execute();
}
else{
    print("Error");}
?>
<a href=" <?php route('/index.php?evenementen=specifiek&evenement_id=' . $id) ?>" >terug naar evenement bekijken</a>
<?php
//load info from database using the id

$stmt = $db->prepare("
SELECT e.id, e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id 
FROM evenement e 
JOIN evenement_soort es ON evenement_soort_id = es.id 
LEFT JOIN locatie l ON l.id = e.locatie_id 
LEFT JOIN adres a ON a.id = e.adres_id
WHERE e.id = $id");
$stmt->execute();

//put the results in $row
$row = $stmt->fetch();

//put the specific info in variables
$titel = $row['titel'];
?>
<form name="evenementWijzigen" method="post" action"" >
    Titel: <input type = "text" name = "titel" value = "<?= $titel?>">
    <input type="Submit" value="Wijzig" />
</form >
