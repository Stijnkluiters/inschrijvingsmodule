<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 29-11-2017
 * Time: 12:08
 */

$db = db();
$leerlingQuery = $db->prepare('SELECT *
  FROM leerling l
  where account_id IN 
  (select account_id from account where rol_id = (
    select rolid from rolnaam where rolnaam = "leerling"))
  ');
$leerlingQuery->execute();
$leerlingen = $leerlingQuery->fetchAll();

if(isset($_POST['delete'])){
    $stmt = $db->prepare('
            UPDATE leerling SET
            deleted = true
            WHERE leerlingnummer = :leerlingnummer');

    $stmt->bindParam('leerlingnummer', $_GET ['leerlingnummer']);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtleerling');
}



?>
<?php foreach ($leerlingen as $leerling) { ?>
<?php } ?>


<form action="<?= route('/index.php?gebruiker=deleteLeerling&leerling_id=' . $_GET['leerling_id'])?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 form-control-label">Studentcode</label>
        <div class="col-md-9">
            <p class="form-control-static"><?= $leerling[ 'leerlingnummer' ] ?></p>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'roepnaam' ] ?>" id="text-input" name="roepnaam" class="form-control" disabled placeholder="<?= $leerling[ 'roepnaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'tussenvoegsel' ] ?>" id="text-input" name="tussenvoegsel" class="form-control" disabled placeholder="<?= $leerling[ 'tussenvoegsel' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'achternaam' ] ?>" id="text-input" name="achternaam" class="form-control" disabled placeholder="<?= $leerling[ 'achternaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'geslacht' ] ?>" id="text-input" name="geslacht" class="form-control" disabled placeholder="<?= $leerling[ 'geslacht' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'geboortedatum' ] ?>" id="email-input" name="geboortedatum" class="form-control" disabled placeholder="<?= $leerling[ 'geboortedatum' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Postcode</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'postcode' ] ?>" id="text-input" name="postcode" class="form-control" disabled placeholder="<?= $leerling[ 'postcode' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Woonplaats</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'plaats' ] ?>" id="text-input" name="plaats" class="form-control" disabled placeholder="<?= $leerling[ 'plaats' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Begin van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'begindatum' ] ?>" id="text-input" name="begindatum" class="form-control" disabled placeholder="<?= $leerling[ 'begindatum' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Eind van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'einddatum' ] ?>" id="text-input" name="einddatum" class="form-control" disabled placeholder="<?= $leerling[ 'einddatum' ] ?>">
        </div>
    </div>
    <?php if(isset($error)) { ?>
        <ul>
            <?php foreach($error as $key => $error) { ?>
                <li><?= $key . ' : ' . $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <button id="delete" name="delete" type="delete" class="btn btn-block btn-primary mb-3">Account
        verwijderen
    </button>
</form>
</html>