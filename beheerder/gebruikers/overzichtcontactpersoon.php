<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 04/12/2017
 * Time: 14:27
 */

/**
 *
 * autorizatie op de tabel rol, zodat een rol niet gebasseerd is op een column die misschien ergens anders voor gebruikt kan worden ( de column: "afkorting" );
 *
 */

$db = db();

/** hier wordt de query voorbereid. in $docentenQuery word een array gemaakt van de query */
$contactenQuery = $db->prepare('SELECT * FROM contactpersoon');
/** pas hier word de query uitgevoer op de achtergrond, een "commit" als het ware */
$contactenQuery->execute();
/** pas hier word de query opgehaald, een "push" */
$contacten = $contactenQuery->fetchAll();

?>

</div>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th>Roepnaam</th>
        <th>Tussenvoegsel</th>
        <th>Achternaam</th>
        <th>functie</th>
        <th>Email</th>
        <th>telefoon</th>
        <th>wijzigen</th>
        <th>verwijderen</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>Roepnaam</th>
        <th>Tussenvoegsel</th>
        <th>Achternaam</th>
        <th>functie</th>
        <th>Email</th>
        <th>telefoon</th>
        <th>wijzigen</th>
        <th>verwijderen</th>
    </tr>
    </tfoot>

    <?php
    /** hier word door middel van een foreach de gemaakte array met de waardes uit de query geprint in tabel vorm */
    foreach ($contacten as $contact)
    {
        ?>
        <tr>
            <td><?= $contact['roepnaam'] ?></td>
            <td><?= $contact['tussenvoegsel'] ?></td>
            <td><?= $contact['achternaam'] ?></td>
            <td><?= $contact['functie'] ?></td>
            <td><?= $contact['email-adres'] ?></td>
            <td><?= $contact['telefoonnr.'] ?></td>
            <td><a href="<?= route('/index.php?gebruiker=editcontactpersoon&contact_id='.$contact['contact_id']); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
            <td><a href="<?= route('/index.php?gebruiker=deletedocent&contact_id='.$contact['contact_id']); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
        </tr>
        <?php
    }
    ?>
</table>