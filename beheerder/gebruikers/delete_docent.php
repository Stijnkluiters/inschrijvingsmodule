<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 30/11/2017
 * Time: 12:05
 */
$afkorting = ($_GET['afkorting']);

$db = db();
$docentQuery = $db->prepare("SELECT * FROM medewerker WHERE afkorting = :afkorting");
$docentQuery->bindParam('afkorting' ,$afkorting, PDO::PARAM_STR);
$docentQuery->execute();
$docent = $docentQuery->fetch();

if(isset($_POST['delete'])){
    $stmt = $db->prepare('
            UPDATE medewerker SET
            deleted = true
            WHERE afkorting = :afkorting');

    $stmt->bindParam('afkorting', $afkorting, PDO::PARAM_STR);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtdocent');
}



?>


<form action="<?= route('/index.php?gebruiker=deletedocent&afkorting=' . $_GET['afkorting'])?>" method="post" enctype="multipart/form-data" class="form-horizontal">
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
            <input type="text" value="<?= $docent[ 'tussenvoegsel' ] ?>" id="text-input" name="tussenvoegsel" class="form-control" disabled placeholder="<?= $docent[ 'tussenvoegsel' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'achternaam' ] ?>" id="text-input" name="achternaam" class="form-control" disabled placeholder="<?= $docent[ 'achternaam' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">functie</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'functie' ] ?>" id="text-input" name="functie" class="form-control" disabled placeholder="<?= $docent[ 'functie' ] ?>">
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
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">locatie</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'locatie' ] ?>" id="text-input" name="locatie" class="form-control" disabled placeholder="<?= $docent[ 'locatie' ] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">telefoon</label>
        <div class="col-md-9">
            <input type="text" value="<?= $docent[ 'telefoon' ] ?>" id="text-input" name="telefoon" class="form-control" disabled placeholder="<?= $docent[ 'telefoon' ] ?>">
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