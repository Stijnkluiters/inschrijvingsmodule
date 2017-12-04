<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 21/11/2017
 * Time: 12:22
 */

/**
 *
 * autorizatie op de tabel rol, zodat een rol niet gebasseerd is op een column die misschien ergens anders voor gebruikt kan worden ( de column: "afkorting" );
 *
 */

$db = db();

/** hier wordt de query voorbereid. in $docentenQuery word een array gemaakt van de query */
$docentenQuery = $db->prepare('SELECT * FROM medewerker WHERE deleted = FALSE ');
/** pas hier word de query uitgevoer op de achtergrond, een "commit" als het ware */
$docentenQuery->execute();
/** pas hier word de query opgehaald, een "push" */
$docenten = $docentenQuery->fetchAll();

?>
<div class="card" >
    <div class="card-header">
        <strong>Medewerkers importeren</strong>
    </div>
    <div class="card-body">
        <p>
            Hier kan je medewerkers importeren d.m.v. csv bestand
        </p>
        <a href="<?= route('/index.php?gebruiker=invoerenmedewerker'); ?>" type="button" class="btn btn-outline-primary btn-lg btn-block">Medewerkers importeren</a>
    </div>
</div>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th>Afkorting</th>
            <th>Roepnaam</th>
            <th>Tussenvoegsel</th>
            <th>Achternaam</th>
            <th>Functie</th>
            <th>Geslacht</th>
            <th>Geboortedatum</th>
            <th>Locatie</th>
            <th>Telefoon</th>
            <th>Wijzigen</th>
            <th>Verwijderen</th>
        </tr>
    </thead>

    <?php
    /** hier word door middel van een foreach de gemaakte array met de waardes uit de query geprint in tabel vorm */
    foreach ($docenten as $docent)
    {
    ?>
    <tr>
        <td><?= $docent['afkorting'] ?></td>
        <td><?= $docent['roepnaam'] ?></td>
        <td><?= $docent['tussenvoegsel'] ?></td>
        <td><?= $docent['achternaam'] ?></td>
        <td><?= $docent['functie'] ?></td>
        <td><?= $docent['geslacht'] ?></td>
        <td><?= $docent['geboortedatum'] ?></td>
        <td><?= $docent['locatie'] ?></td>
        <td><?= $docent['telefoon'] ?></td>
        <td><a href="<?= route('/index.php?gebruiker=editmedewerker&afkorting='.$docent['afkorting']); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
        <td><a href="<?= route('/index.php?gebruiker=deletemedewerker&afkorting='.$docent['afkorting']); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
    </tr>
    <?php
    }
    ?>
</table>


