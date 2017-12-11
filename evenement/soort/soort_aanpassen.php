<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/6/2017
 * Time: 14:04
 */

//create database
$db = db();

//create a variable to catch errors
$error = [];
$soort = $_GET['soort'];

//filter and check the content in the post variable
/**soortnaam*/
if (!isset($_POST['soortnaam']) || empty($_POST['soortnaam'])) {
    $error['soortnaam'] = ' soortnaam is verplicht.';
}
$soortnaam = filter_input(INPUT_POST, 'soortnaam', FILTER_SANITIZE_STRING);
if (empty($soortnaam)) {
    $error['soortnaam'] = ' het filteren van de soortnaam ging verkeerd';
}

/** benodigdheden */
$benodigdheid = filter_input(INPUT_POST, 'omschrijving', FILTER_SANITIZE_STRING);

//if there are no errors, continue with the query
if (count($error) === 0) {
    $stmt = $db->prepare('
    UPDATE `soort` SET 
    `soort`=?,
    `benodigdheid`=?
    WHERE 
    `soort`= ?
');
    //connect the variables to the information in the query
    $stmt->execute(array(
        $soortnaam,
        $benodigdheid,
        $soort));

}


$stmt2 = $db->prepare('
SELECT soort, benodigdheid
FROM soort
WHERE soort = ?');

$stmt2->execute(array($soort));

$prevalue = $stmt2->fetch();
?>
<!-- form where user can insert a 'soort' -->
<div class="card">
    <h4 class="card-header">Soort wijzigen
        <div class='right' class="btn btn-primary">
            <a href="<?= route('/index.php?soorten=overzicht') ?>"class="pull-right" class="btn btn-primary">terug naar soortoverzicht</a>
        </div>
    </h4>
    <form name="evenementWijzigen" method="post"
          action="<?php echo filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING); ?>">
        <div class="col-sm-12">

            <div class="card-body">
                <div class="form-group">
                    <label for="company">Soortnaam*</label>
                    <input type="text" class="form-control" id="soortnaam" name="soortnaam" placeholder="Soortnaam"
                           value="<?= $prevalue['soort']; ?>"/>
                </div>
                <div class="form-group">
                    <label for="omschrijving">Omschrijving</label>
                    <textarea class="form-control" id="omschrijving" name="omschrijving"
                              placeholder="Omschrijving voor het evenement"><?= $prevalue['benodigdheid']; ?></textarea>
                </div>
                <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-sm btn-primary">Toevoegen
                </div>
            </div>
        </div>
    </form>
</div>
