<?php

/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 21-11-2017
 * Time: 11:11
 */
$db = db();
$leerlingQuery = $db->prepare('SELECT *
  FROM leerling l
  where account_id IN 
  (select account_id from account where rol_id = (
    select rolid from rolnaam where rolnaam = "leerling"
  )
  )
  ORDER BY leerlingnummer asc
  ');
$leerlingQuery->execute();
$leerlingen = $leerlingQuery->fetchAll();


?>
<div class="card" >
    <div class="card-header">
        <strong>Leerlingen importeren</strong>
    </div>
    <div class="card-body">
        <p>
            Hier kan je leerlingen importeren d.m.v. csv bestand
        </p>
        <a href="<?= route('/index.php?gebruiker=invoerenleerling'); ?>" type="button" class="btn btn-outline-primary btn-lg btn-block">Leerlingen importeren</a>
    </div>
</div>
<?php


if (count($leerlingen)) { ?>
    <table id="dataTable" class="display">
    <thead>
    <tr>
        <th>Student</th>
        <th>Geslacht</th>
        <th>Roepnaam</th>
        <th>Voorvoegsel</th>
        <th>Achternaam</th>
        <th>Geboortedatum</th>
        <th>Postcode</th>
        <th>Plaats</th>
        <th>Opleiding</th>
        <th>Opleiding Begin</th>
        <th>Opleiding Eind</th>
        <th>Wijzigen</th>
        <th>Verwijderen</th>
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
            <th>Postcode</th>
            <th>Plaats</th>
            <th>Opleiding</th>
            <th>Opleiding Begin</th>
            <th>Opleiding Eind</th>
            <th>Wijzigen</th>
            <th>Verwijderen</th>
        </tr>
        </tfoot>

    <tbody>

    <?php
    foreach ($leerlingen as $leerling) { ?>
        <tr>
            <td><?= $leerling['leerlingnummer'] ?></td>
            <td><?= $leerling['geslacht'] ?></td>
            <td><?= ucfirst($leerling['roepnaam']); ?></td>
            <td><?= $leerling['tussenvoegsel'] ?></td>
            <td><?= ucfirst($leerling['achternaam']) ?></td>
            <td><?= date('d-M-Y',strtotime($leerling['geboortedatum'])); ?></td>
            <td><?= $leerling['postcode'] ?></td>
            <td><?= ucfirst($leerling['plaats']) ?></td>
            <td><?= $leerling['opleiding'] ?></td>
            <td><?= date('d-M-Y',strtotime($leerling['begindatum'])); ?></td>
            <td><?= date('d-M-Y',strtotime($leerling['einddatum'])); ?></td>
            <td><a href="<?= route('/index.php?gebruiker=editleerling&leerlingnummer=' . $leerling['leerlingnummer']); ?>"><i
                            class="fa fa-pencil" aria-hidden="true"></i></a></td>
            <td><a href="<?= route('/index.php?gebruiker=deleteLeerling&leerlingnummer=' . $leerling['leerlingnummer']); ?>"><i
                            class="fa fa-trash" aria-hidden="true"></i></a></td>
        </tr>
        <?php } ?>
    </tbody>

    </table>
<?php } else { ?>

    <h2> Er zijn geen leerlingen gevonden. </h2>

<?php } ?>
