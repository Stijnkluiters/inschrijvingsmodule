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

        $soortnaam = filter_input(INPUT_POST, 'soortnaam', FILTER_SANITIZE_STRING);
        if (empty($soortnaam)) {
            $error['soortnaam'] = ' het filteren van de soortnaam ging verkeerd';
        }

        /** benodigdheden */
        $benodigdheden = filter_input(INPUT_POST, 'benodigdheden', FILTER_SANITIZE_STRING);

//if there are no errors, continue with the query
        if (count($error) === 0) {
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