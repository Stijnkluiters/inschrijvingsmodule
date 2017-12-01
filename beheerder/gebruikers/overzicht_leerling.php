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
  ))
  ');
$leerlingQuery->execute();
$leerlingen = $leerlingQuery->fetchAll();


?>
<?php
if (count($leerlingen)) { ?>
    <table class="table">
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
        <th>Edit</th>
        <th>Deleted</th>
    </tr>
    </thead>

    <tbody>


    <?php
    foreach ($leerlingen as $leerling) { ?>
        <tr>
            <td><?= $leerling['leerlingnummer'] ?></td>
            <td><?= $leerling['geslacht'] ?></td>
            <td><?= $leerling['roepnaam'] ?></td>
            <td><?= $leerling['tussenvoegsel'] ?></td>
            <td><?= $leerling['achternaam'] ?></td>
            <td><?= $leerling['geboortedatum'] ?></td>
            <td><?= $leerling['postcode'] ?></td>
            <td><?= $leerling['plaats'] ?></td>
            <td><?= $leerling['opleiding'] ?></td>
            <td><?= $leerling['begindatum'] ?></td>
            <td><?= $leerling['einddatum'] ?></td>
            <td><a href="<?= route('/index.php?gebruiker=editleerling&leerling_id=' . $leerling['leerlingnummer']); ?>"><i
                            class="fa fa-pencil" aria-hidden="true"></i></a></td>
            <td><a href="<?= route('/index.php?gebruiker=deleteLeerling&leerling_id=' . $leerling['leerlingnummer']); ?>"><i
                            class="fa fa-trash" aria-hidden="true"></i></a></td>
        </tr>
        <?php } ?>
    </tbody>

    </table>
<?php } else { ?>

    <h2> Er zijn geen leerlingen gevonden. </h2>

<?php } ?>
