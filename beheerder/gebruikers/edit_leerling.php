<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 22-11-2017
 * Time: 13:08
 */

$db = db();
$leerlingQuery = $db->prepare('SELECT *
  FROM leerling l
  where account_id IN 
  (select account_id from account where rol_id = (
    select rolid from rolnaam where rolnaam = "leerling"
  ))
  ');
$leerlingQuery->execute();
$leerling = $leerlingQuery->fetch();

if(isset($_POST['submit'])){

        /**
         * Filter input from user, which is required in order to continue the request->post.
         */
        /** gebruikersnaam */
        $error = array();


        /** Roepnaam */
        if (!isset($_POST['roepnaam']) || empty($_POST['roepnaam'])) {
            $error['roepnaam'] = ' Roepnaam is verplicht';
        }
        $roepnaam = filter_input(INPUT_POST, 'roepnaam', FILTER_SANITIZE_STRING);
        if (empty($roepnaam)) {
            $error['roepnaam'] = ' Het filteren van roepnaam ging verkeerd';
        }
        $roepnaam = strtolower($roepnaam);
        /** Voorvoegsel */

        $tussenvoegsel = filter_input(INPUT_POST, 'tussenvoegsel', FILTER_SANITIZE_STRING);
        if ($tussenvoegsel === false) {
            $error['tussenvoegsel'] = ' het filteren van tussenvoegsel ging verkeerd';
        }
        $tussenvoegsel = strtolower($tussenvoegsel);
        /** Achternaam */
        if (!isset($_POST['achternaam']) || empty($_POST['achternaam'])) {
            $error['achternaam'] = ' Achternaam is verplicht';
        }
        $achternaam = filter_input(INPUT_POST, 'achternaam', FILTER_SANITIZE_STRING);
        if (empty($achternaam)) {
            $error['achternaam'] = ' het filteren van achternaam ging verkeerd';
        }
        $achternaam = strtolower($achternaam);

        /** geboortedatum */
        if (!isset($_POST['geboortedatum']) || empty($_POST['geboortedatum'])) {
            $error['geboortedatum'] = ' Geboortedatum is verplicht';
        }
// check if given date can be converted to strtotime, if not. its false which means incorrect date.
        if (!strtotime($_POST['geboortedatum'])) {
            $error['geboortedatum'] = ' Geboortedatum moet een datum zijn.';
        }
        $geboortedatum = filter_input(INPUT_POST, 'geboortedatum', FILTER_SANITIZE_STRING);
        if (empty($geboortedatum)) {
            $error['geboortedatum'] = ' het filteren van geboortedatum ging verkeerd';
        }
        /** geslacht */
        if (!isset($_POST['geslacht']) || empty($_POST['geslacht'])) {
            $error['geslacht'] = ' Geslacht is verplicht.';
        }
        $geslacht = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
        if (empty($geslacht)) {
            $error['geslacht'] = ' het filteren van geslacht ging verkeerd';
        }
        if (!isset($_POST['postcode']) || empty($_POST['postcode'])) {
            $error['postcode'] = ' postcode is verplicht.';
        }
        $geslacht = filter_input(INPUT_POST, 'geslacht', FILTER_SANITIZE_STRING);
        if (empty($geslacht)) {
            $error['postcode'] = ' het filteren van postcode ging verkeerd';
        }

    if(count($error ) === 0){

    /**
     * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
     */
    $stmt = $db->prepare('
            UPDATE leerling SET
            roepnaam = :roepnaam, 
            tussenvoegsel = :tussenvoegsel, 
            achternaam = :achternaam,
            opleiding = :opleiding, 
            geboortedatum = :geboortedatum,
            postcode = :postcode,
            plaats = :plaats, 
            geslacht = :geslacht
            WHERE leerlingnummer = :leerlingnummer');

    $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
    $stmt->bindParam('tussenvoegsel', $tussenvoegsel, PDO::PARAM_STR);
    $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
    $stmt->bindParam('opleiding', $opleiding);
    $stmt->bindParam('geboortedatum', $geboortedatum);
    $stmt->bindParam('postcode', $postcode);
    $stmt->bindParam('plaats', $plaats);
    $stmt->bindParam('geslacht', $geslacht);
    $stmt->bindParam('leerlingnummer', $_GET ['leerlingnummer']);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtleerling');

    }
}



?>

<form action="<?= route('/index.php?gebruiker=editleerling&leerlingnummer=' . $_GET['leerlingnummer'])?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 form-control-label">Leerlingnummer</label>
        <div class="col-md-9">
            <p class="form-control-static"><?= $leerling[ 'leerlingnummer' ] ?></p>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'roepnaam' ] ?>" id="text-input" name="roepnaam" class="form-control" placeholder="<?= $leerling[ 'roepnaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'tussenvoegsel' ] ?>" id="text-input" name="tussenvoegsel" class="form-control" placeholder="<?= $leerling[ 'tussenvoegsel' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'achternaam' ] ?>" id="text-input" name="achternaam" class="form-control" placeholder="<?= $leerling[ 'achternaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'geslacht' ] ?>" id="text-input" name="geslacht" class="form-control" placeholder="<?= $leerling[ 'geslacht' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'geboortedatum' ] ?>" id="email-input" name="geboortedatum" class="form-control" placeholder="<?= $leerling[ 'geboortedatum' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Postcode</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'postcode' ] ?>" id="text-input" name="postcode" class="form-control" placeholder="<?= $leerling[ 'postcode' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Woonplaats</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'plaats' ] ?>" id="text-input" name="plaats" class="form-control" placeholder="<?= $leerling[ 'plaats' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Opleiding</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'opleiding' ] ?>" id="text-input" name="plaats" class="form-control" placeholder="<?= $leerling[ 'opleiding' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Begin van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'begindatum' ] ?>" id="text-input" name="begindatum" class="form-control" placeholder="<?= $leerling[ 'begindatum' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Eind van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'einddatum' ] ?>" id="text-input" name="einddatum" class="form-control" placeholder="<?= $leerling[ 'einddatum' ] ?>">
        </div>
    </div>
        <?php if(isset($error)) { ?>
    <ul>
    <?php foreach($error as $key => $error) { ?>
        <li><?= $key . ' : ' . $error; ?></li>
    <?php } ?>
    </ul>
    <?php } ?>
    <button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Accoun wijzigen
    </button>
</form>
</html>