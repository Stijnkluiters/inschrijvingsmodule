
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
if(isset($_POST['titel'])) {
    if (($_POST['titel']) !== '') {
        $titel = $_POST['titel'];
        $onderwerp = $_POST['onderwerp'];
        $datum = $_POST['datum'];
        $locatie = $_POST['locatie'];
        $lokaalnr = $_POST['lokaalnummer'];
        $update = true;
    }
}
else{
    $titel_update = '';
    $update = false;
}

if($update == true){
    $stmt = $db->prepare('
            UPDATE evenement SET
            titel = :titel
            onderwerp = :ondewerp
            datum = :datum
            locatie = :locatie
            WHERE evenement_id = :id');
    $stmt->bindParam('titel', $titel, PDO::PARAM_STR);
    $stmt->bindParam('onderwerp', $onderwerp, PDO::PARAM_STR);
    $stmt->bindParam('datum', $datum, PDO::PARAM_STR);
    $stmt->bindParam('locatie', $locatie, PDO::PARAM_STR);
    $stmt->bindParam('id', $id);
    $stmt->execute();
}
//load info from database using the id

$stmt = $db->prepare("
SELECT e.titel, e.onderwerp, e.datum, e.locatie, e.lokaalnummer, e.begintijd, e.eindtijd, e.vervoer, e.min_leerlingen, e.max_leerlingen, e.soort, e.contactnr
FROM evenement e 
JOIN soort s ON s.soort = e.soort
WHERE evenement_id = $id");
$stmt->execute();

//put the results in $row
$row = $stmt->fetch();

//put the specific info in variables
$titel = $row['titel'];
$onderwerp = $row['onderwerp'];
$datum = $row['datum'];
$locatie = $row['locatie'];
$lokaalnr = $row['lokaalnummer'];
?>
<form name="evenementWijzigen" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES); ?>" >
    Titel: <input type = "text" name = "titel" value = "<?= $titel?>"><br>
    Onderwerp: <input type = "text" name = "onderwerp" value = "<?= $onderwerp?>"><br>
    datum: <input type = "text" name = "datum" value = "<?= $datum?>"><br>
    locatie: <input type = "text" name = "locatie" value = "<?= $locatie?>"><br>
    <!--lokaalnummer: <input type = "text" name = "lokaalnummer" value = "<?= $lokaalnr?>"><br>-->
    <input type="Submit" value="Wijzig" />
</form >
