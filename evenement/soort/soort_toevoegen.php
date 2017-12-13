<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/6/2017
 * Time: 10:01
 */
//create database
$db = db();

//create a variable to catch errors
$error = [];
$successmessage = "";

//filter and check the content in the post variable
/**soortnaam*/
if (isset($_POST)) {
    if (!isset($_POST['soortnaam']) || empty($_POST['soortnaam'])) {
        $error['soortnaam'] = ' soortnaam is verplicht.';
    }
    $soortnaam = filter_input(INPUT_POST, 'soortnaam', FILTER_SANITIZE_STRING);
    if (empty($soortnaam)) {
        $error['soortnaam'] = ' het filteren van de soortnaam ging verkeerd';

    } else {
        //kijk of er al een soort is die dezelfde naam heeft als de ingevoerde naam
        $stmt2 = $db->prepare("
SELECT soort, benodigdheid
FROM soort
WHERE soort = :soortnaam");
        $stmt2->bindParam("soortnaam", $soortnaam);
        $stmt2->execute();
        $results = $stmt2->fetch();
        if (!empty($results)) {
            $noupdate = false;
        } else {
            $noupdate = true;
        }

        $soortnaam = filter_input(INPUT_POST, 'soortnaam', FILTER_SANITIZE_STRING);
        if (empty($soortnaam)) {
            $error['soortnaam'] = ' het filteren van de soortnaam ging verkeerd';
        }

        /** benodigdheden */
        $benodigdheden = filter_input(INPUT_POST, 'benodigdheden', FILTER_SANITIZE_STRING);

//if there are no errors, continue with the query
        if (count($error) === 0) {
            if ($noupdate === true) {
                $stmt = $db->prepare("
    INSERT INTO soort (
    soort, benodigdheid
    )VALUES (
        ?,
        ?
    )");
                //connect the variables to the information in the query
                $stmt->execute(array(
                    $soortnaam,
                    $benodigdheden));
                $successmessage = "<div><span class='bg-success'>$soortnaam is toegevoegd</span></div>";

            } elseif ($noupdate === false) {
                $stmt = $db->prepare("
        UPDATE soort
        SET actief = 1
        WHERE soort = :soortnaam");
                $stmt->bindParam("soortnaam", $soortnaam);
                $stmt->execute();
                $successmessage = "<div><span class='bg-warning'>$soortnaam was al eerder toegevoegd en is opnieuw geactiveerd, benodigdheden zijn aan te passen vanaf het overzicht</span></div>";
            }
        } else {
            $successmessage = "";
        }
    }
}
?>
<!-- form where user can insert a 'soort' -->
<div class="card">
    <div class="card-header">
        <h4>Soort toevoegen
            <div class='pull-right'>
                <a href="<?= route('/index.php?soorten=overzicht') ?>" class=" btn btn-primary">Terug naar
                    soortenoverzicht</a>
            </div>
        </h4>
    </div>
    <form name="evenementWijzigen" method="post"
          action="<?php echo filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING); ?>">
        <div class="col-sm-12">
            <div class="card-body">
                <div class="form-group">
                    <label for="company">soortnaam*</label>
                    <input type="text" class="form-control" id="soortnaam" name="soortnaam"
                           placeholder="evenementsoort">
                </div>

                <div class="form-group">
                    <label for="omschrijving">benodigdheden</label>
                    <textarea class="form-control" id="benodigdheden" name="benodigdheden"
                              placeholder="benodigdheden bij soort evenement"></textarea>
                </div>
                <?= $successmessage ?>
                <button type="submit" name="submit" class="btn btn-sm btn-primary">Toevoegen
            </div>
        </div>
    </form>
</div>