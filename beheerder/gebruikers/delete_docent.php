<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 30/11/2017
 * Time: 12:05
 */
$db = db();
$docentQuery = $db->prepare('SELECT 
          g.id,
          g.afkorting,
          g.geslacht,
          g.roepnaam,
          g.voorvoegsel,
          g.achternaam,
          g.geboortedatum 
FROM gebruiker g 
JOIN adres a ON g.adres_id = a.id 
JOIN gebruiker_heeft_rol gr ON g.id = gr.gebruiker_id
JOIN rol r ON r.id = gr.rol_id
WHERE r.naam = "docent"');
$docentQuery->execute();
$docenten = $docentQuery->fetchAll();

if(isset($_POST['delete'])){
    $stmt = $db->prepare('
            UPDATE gebruiker SET
            deleted = true
            WHERE id = :gebruiker_id');

    $stmt->bindParam('gebruiker_id', $_GET ['gebruiker_id']);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtdocent');
}



?>
<?php foreach ($docenten as $docent) { ?>
<?php } ?>


<form action="<?= route('/index.php?gebruiker=deletedocent&gebruiker_id=' . $_GET['gebruiker_id'])?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 form-control-label">afkorting</label>
        <div class="col-md-9">
            <p class="form-control-static"><?= $docent[ 'afkorting' ] ?></p>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'roepnaam' ] ?>" id="text-input" name="roepnaam" class="form-control" disabled placeholder="<?= $docent[ 'roepnaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'voorvoegsel' ] ?>" id="text-input" name="voorvoegsel" class="form-control" disabled placeholder="<?= $docent[ 'voorvoegsel' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'achternaam' ] ?>" id="text-input" name="achternaam" class="form-control" disabled placeholder="<?= $docent[ 'achternaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Geslacht</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'geslacht' ] ?>" id="text-input" name="geslacht" class="form-control" disabled placeholder="<?= $docent[ 'geslacht' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="email-input">Geboortedatum</label>
        <div class="col-md-9">
            <input type="date" value="<?= $docent[ 'geboortedatum' ] ?>" id="email-input" name="geboortedatum" class="form-control" disabled placeholder="<?= $docent[ 'geboortedatum' ] ?>">
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