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
        <th>bedrijfinfo</th>
        <th>functie</th>
        <th>Email</th>
        <th>telefoon</th>
        <th>wijzigen</th>
        <th>verwijderen</th>
    </tr>
    </tfoot>

    <?php
    if (empty($contacten))
    {
        print("<h2>Geen contactpersonen gevonden</h2>");
    }else{

        /** hier word door middel van een foreach de gemaakte array met de waardes uit de query geprint in tabel vorm */
        foreach ($contacten as $contact) {
            ?>
            <tr>
                <td><?= ucfirst($contact['roepnaam']) ?></td>
                <td><?= $contact['tussenvoegsel'] ?></td>
                <td><?= ucfirst($contact['achternaam']) ?></td>
                <td><a href="<?= route('/index.php?bedrijfsinfo=wijzigen&contactpersoon='.$contact['contact_id']); ?>">Klik hier</a>om de bedrijfsgegevens te bekijken </td>
                <td><?= $contact['functie'] ?></td>
                <td><?= $contact['email-adres'] ?></td>
                <td><?= $contact['telefoonnr.'] ?></td>
                <td>
                    <a href="<?= route('/index.php?gebruiker=editcontactpersoon&contact_id=' . $contact['contact_id']); ?>"><i
                                class="fa fa-pencil" aria-hidden="true"></i></a></td>
                <td>
                    <?php
                    if ($contact['deleted'] == true) {
                        ?>
                        <a href="<?= route('/index.php?gebruiker=activatiecontactpersoon&contact_id=' . $contact['contact_id']); ?>">
                            <i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php
                    } else {
                        ?>
                        <a href="<?= route('/index.php?gebruiker=deletecontactpersoon&contact_id=' . $contact['contact_id']); ?>">
                            <i class="fa fa-check" aria-hidden="false"></i></a>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        }
        ?>
    </table>
<?php