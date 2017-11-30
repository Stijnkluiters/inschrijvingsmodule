<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 22-11-2017
 * Time: 13:08
 */

$db = db();
$leerlingQuery = $db->prepare('SELECT 
          g.id,
          g.studentcode,
          g.geslacht,
          g.roepnaam,
          g.voorvoegsel,
          g.achternaam,
          g.geboortedatum,
          a.postcode,
          a.plaatsnaam,
          g.opleiding_start,
          g.opleiding_eind 
FROM gebruiker g 
JOIN adres a ON g.adres_id = a.id 
JOIN gebruiker_heeft_rol gr ON g.id = gr.gebruiker_id
JOIN rol r ON r.id = gr.rol_id
WHERE r.naam = "leerling"');
$leerlingQuery->execute();
$leerlingen = $leerlingQuery->fetchAll();

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
        $voorvoegsel = filter_input(INPUT_POST, 'voorvoegsel', FILTER_SANITIZE_STRING);
        if ($voorvoegsel === false) {
            $error['voorvoegsel'] = ' het filteren van voorvoegsel ging verkeerd';
        }
        $voorvoegsel = strtolower($voorvoegsel);
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

    if(count($error ) === 0){


    /**
     * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
     */
    $stmt = $db->prepare('
            UPDATE gebruiker SET
            roepnaam = :roepnaam, 
            voorvoegsel = :voorvoegsel, 
            achternaam = :achternaam, 
            geboortedatum = :geboortedatum, 
            geslacht = :geslacht
            WHERE id = :gebruiker_id');
            
    $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
    $stmt->bindParam('voorvoegsel', $voorvoegsel, PDO::PARAM_STR);
    $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
    $stmt->bindParam('geboortedatum', $geboortedatum);
    $stmt->bindParam('geslacht', $geslacht);
    $stmt->bindParam('gebruiker_id', $_GET ['gebruiker_id']);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtleerling');
    }
}



?>
<?php foreach ($leerlingen as $leerling) { ?>
<?php } ?>


<form action="<?= route('/index.php?gebruiker=editleerling&gebruiker_id=' . $_GET['gebruiker_id'])?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 form-control-label">Studentcode</label>
        <div class="col-md-9">
            <p class="form-control-static"><?= $leerling[ 'studentcode' ] ?></p>
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
            <input type="text" value="<?= $leerling[ 'voorvoegsel' ] ?>" id="text-input" name="voorvoegsel" class="form-control" placeholder="<?= $leerling[ 'voorvoegsel' ] ?>">
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
            <input type="text" value="<?= $leerling[ 'plaatsnaam' ] ?>" id="text-input" name="plaatsnaam" class="form-control" placeholder="<?= $leerling[ 'plaatsnaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Begin van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'opleiding_start' ] ?>" id="text-input" name="opleiding_start" class="form-control" placeholder="<?= $leerling[ 'opleiding_start' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Eind van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'opleiding_eind' ] ?>" id="text-input" name="opleiding_einde" class="form-control" placeholder="<?= $leerling[ 'opleiding_eind' ] ?>">
        </div>
    </div>
    <?php if(isset($error)) { ?>
    <ul>
    <?php foreach($error as $key => $error) { ?>
        <li><?= $key . ' : ' . $error; ?></li>
    <?php } ?>
    </ul>
    <?php } ?>
    <button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Account
        wijzigen
    </button>
</form>
</html>