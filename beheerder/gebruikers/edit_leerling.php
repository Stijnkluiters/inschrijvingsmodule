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

?>
<?php foreach ($leerlingen as $leerling) { ?>
<?php } ?>


<form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 form-control-label">Studentcode</label>
        <div class="col-md-9">
            <p class="form-control-static"><?= $leerling[ 'studentcode' ] ?></p>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'roepnaam' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'roepnaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'voorvoegsel' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'voorvoegsel' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'achternaam' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'achternaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'geslacht' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'geslacht' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'geboortedatum' ] ?>" id="email-input" name="email-input" class="form-control" placeholder="<?= $leerling[ 'geboortedatum' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Postcode</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'postcode' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'postcode' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Woonplaats</label>
        <div class="col-md-9">
            <input type="text" value="<?= $leerling[ 'plaatsnaam' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'plaatsnaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Begin van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'opleiding_start' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'opleiding_start' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Eind van de opleiding</label>
        <div class="col-md-9">
            <input type="date" value="<?= $leerling[ 'opleiding_eind' ] ?>" id="text-input" name="text-input" class="form-control" placeholder="<?= $leerling[ 'opleiding_eind' ] ?>">
        </div>
    </div>

</form>
<a href="<?= route('/index.php?gebruiker=overzichtleerling'); ?>">submit</a>
