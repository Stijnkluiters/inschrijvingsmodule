<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 04/12/2017
 * Time: 15:02
 */


$db = db();
$contact_id = filter_var(filter_input(INPUT_GET,'contact_id',FILTER_SANITIZE_STRING),FILTER_VALIDATE_INT);





if (isset($_POST['submit'])) {
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

    /** tussenvoegsel */
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

    /** functie */
    if (!isset($_POST['functie']) || empty($_POST['functie'])) {
        $error['functie'] = ' functie is verplicht.';
    }
    $functie = filter_input(INPUT_POST, 'functie', FILTER_SANITIZE_STRING);
    if (empty($functie)) {
        $error['functie'] = ' het filteren van functie ging verkeerd';
    }


    /** telefoonnummer */
    if (!isset($_POST['telefoon']) || empty($_POST['telefoon'])) {
        $error['telefoon'] = ' telefoonnummer is verplicht.';
    }
    $telefoonnummer = filter_input(INPUT_POST, 'telefoon', FILTER_SANITIZE_STRING);
    if (empty($telefoonnummer)) {
        $error['telefoon'] = ' het filteren van telefoon ging verkeerd';
    }

    /** email */
    if (!isset($_POST['Email']) || empty($_POST['Email'])) {
    $error['Email'] = ' email is verplicht.';
    }
    $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_STRING);
    if (empty($email)) {
        $error['Email'] = ' het filteren van email ging verkeerd';
    }



    if (count($error) === 0) {


        /**
         * Filteren is gedaan, als er geen errors aanwezig zijn. voer de gegevens dan in de database.
         */
        $stmt = $db->prepare('
            UPDATE contactpersoon SET
            contact_id = :contact_id,
            roepnaam = :roepnaam, 
            tussenvoegsel = :tussenvoegsel, 
            achternaam = :achternaam,
            functie = :functie, 
            `telefoonnr.` = :telefoonnummer,
            `email-adres` = :email
            WHERE contact_id = :contact_id');

        $stmt->bindParam('contact_id', $contact_id, PDO::PARAM_STR);
        $stmt->bindParam('roepnaam', $roepnaam, PDO::PARAM_STR);
        $stmt->bindParam('tussenvoegsel', $tussenvoegsel, PDO::PARAM_STR);
        $stmt->bindParam('achternaam', $achternaam, PDO::PARAM_STR);
        $stmt->bindParam('functie', $functie, PDO::PARAM_STR);
        $stmt->bindParam('telefoonnummer', $telefoonnummer, PDO::PARAM_STR);
        $stmt->bindParam('email', $email, PDO::PARAM_STR);
        $stmt->bindParam('contact_id', $contact_id,PDO::PARAM_STR);
        $stmt->execute();
        redirect('/index.php?gebruiker=overzichtcontactpersonen', $roepnaam.' is aangepast.');
    } else {
        dump($error);
        exit;
    }
}

$contactQuery = $db->prepare("SELECT * FROM contactpersoon WHERE contact_id = :contact_id");
$contactQuery->bindParam('contact_id' ,$contact_id, PDO::PARAM_STR);
$contactQuery->execute();
$contact = $contactQuery->fetch();

?>


<form action="<?= route('/index.php?gebruiker=editcontactpersoon&contact_id=' . $_GET['contact_id']) ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Naam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['roepnaam'] ?>" id="text-input" name="roepnaam" class="form-control"
                   placeholder="<?= $contact['roepnaam'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Tussenvoegsel</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['tussenvoegsel'] ?>" id="text-input" name="tussenvoegsel"
                   class="form-control" placeholder="<?= $contact['tussenvoegsel'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">Achternaam</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['achternaam'] ?>" id="text-input" name="achternaam" class="form-control"
                   placeholder="<?= $contact['achternaam'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">functie</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['functie'] ?>" id="text-input" name="functie" class="form-control"
                   placeholder="<?= $contact['functie'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 form-control-label" for="text-input">telefoon</label>
        <div class="col-md-9">
            <input type="text" value="<?= $contact['telefoonnr.'] ?>" id="text-input" name="telefoon" class="form-control"
                   placeholder="<?= $contact['telefoonnummer'] ?>">
        </div>
    </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label" for="text-input">Email</label>
            <div class="col-md-9">
                <input type="text" value="<?= $contact['email-adres'] ?>" id="text-input" name="Email" class="form-control"
                       placeholder="<?= $contact['email'] ?>">
            </div>
        </div>
    <?php if (isset($error)) { ?>
        <ul>
            <?php foreach ($error as $key => $error) { ?>
                <li><?= $key . ' : ' . $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <button id="submit" name="submit" type="submit" class="btn btn-block btn-primary mb-3">Account
        wijzigen
    </button>
</form>
</html>