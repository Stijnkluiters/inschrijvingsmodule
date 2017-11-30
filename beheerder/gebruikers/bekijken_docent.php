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
$docentenQuery = $db->prepare('SELECT 
g.afkorting as afkorting, 
g.roepnaam  as roepnaam, 
g.voorvoegsel as voorvoegsel, 
g.achternaam as achternaam, 
g.geslacht as geslacht, 
g.geboortedatum as geboortedatum  
FROM gebruiker g 
JOIN gebruiker_heeft_rol gr ON g.id = gr.gebruiker_id
JOIN rol r ON r.id = gr.rol_id
WHERE r.naam = "docent" ');
/** pas hier word de query uitgevoer op de achtergrond, een "commit" als het ware */
$docentenQuery->execute();
/** pas hier word de query opgehaald, een "push" */
$docenten = $docentenQuery->fetchAll();

?>
<div class="card" >
    <div class="card-header">
        <strong>Docenten importeren</strong>
    </div>
    <div class="card-body">
        <p>
            Hier kan je docenten importeren d.m.v. csv bestand
        </p>
        <a href="<?= route('/index.php?gebruiker=invoerendocent'); ?>" type="button" class="btn btn-outline-primary btn-lg btn-block">Docenten importeren</a>
    </div>
</div>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th>afkorting</th>
            <th>Roepnaam</th>
            <th>Voorvoegsel</th>
            <th>Achternaam</th>
            <th>Geslacht</th>
            <th>Geboortedatum</th>
        </tr>
    </thead>
    <tfoot>
    <tr>
        <th>Student</th>
        <th>Geslacht</th>
        <th>Roepnaam</th>
        <th>Voorvoegsel</th>
        <th>Achternaam</th>
        <th>Geboortedatum</th>
    </tr>
    </tfoot>

    <?php
    /** hier word door middel van een foreach de gemaakte array met de waardes uit de query geprint in tabel vorm */
    foreach ($docenten as $docent)
    {
    ?>
    <tr>
        <td><?= $docent['afkorting'] ?></td>
        <td><?= $docent['roepnaam'] ?></td>
        <td><?= $docent['voorvoegsel'] ?></td>
        <td><?= $docent['achternaam'] ?></td>
        <td><?= $docent['geslacht'] ?></td>
        <td><?= $docent['geboortedatum'] ?></td>
        <td><a href="<?= route('/index.php?gebruiker=editdocent&gebruiker_id='.$docent['id']); ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
        <td><a href="<?= route('/index.php?gebruiker=deletedocent&gebruiker_id='.$docent['id']); ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
    </tr>
    <?php
    }
    ?>
</table>


