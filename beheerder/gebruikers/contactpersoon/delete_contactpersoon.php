<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 05/12/2017
 * Time: 12:03
 */


$contact_id = filter_var(filter_input(INPUT_GET,'contact_id',FILTER_SANITIZE_STRING),FILTER_VALIDATE_INT);

$db = db();
$contactQuery = $db->prepare("SELECT * FROM contactpersoon WHERE contact_id = :contact_id");
$contactQuery->bindParam('contact_id' ,$contact_id, PDO::PARAM_STR);
$contactQuery->execute();
$contact = $contactQuery->fetch();

if(isset($_POST['delete'])){
    $stmt = $db->prepare('
            UPDATE contactpersoon SET
            deleted = true
            WHERE contact_id = :contact_id');

    $stmt->bindParam('contact_id', $contact_id, PDO::PARAM_STR);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtcontactpersonen', $contact['roepnaam'] . ' is verwijderd!');

}



?>


<form action="<?= route('/index.php?gebruiker=deletecontactpersoon&contact_id=' . $_GET['contact_id']) ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['roepnaam'] ?>" id="text-input" name="roepnaam" class="form-control"
                   disabled placeholder="<?= $contact['roepnaam'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['tussenvoegsel'] ?>" id="text-input" name="tussenvoegsel"
                   class="form-control" disabled placeholder="<?= $contact['tussenvoegsel'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['achternaam'] ?>" id="text-input" name="achternaam" class="form-control"
                   disabled placeholder="<?= $contact['achternaam'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">functie</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['functie'] ?>" id="text-input" name="functie" class="form-control"
                   disabled placeholder="<?= $contact['functie'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">telefoon</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['telefoonnummer'] ?>" id="text-input" name="telefoon" class="form-control"
                   disabled placeholder="<?= $contact['telefoonnummer'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Email</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['email'] ?>" id="text-input" name="Email" class="form-control"
                   disabled placeholder="<?= $contact['email'] ?>">
        </div>
    </div>
    <?php if (isset($error)) { ?>
        <ul>
            <?php foreach ($error as $key => $error) { ?>
                <li><?= $key . ' : ' . $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <button id="delete" name="delete" type="submit" class="btn btn-block btn-primary mb-3">Account
        verwijderen
    </button>
</form>
</html>