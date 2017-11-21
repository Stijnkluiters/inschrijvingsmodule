<?php

/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 21-11-2017
 * Time: 11:11
 */
$db = db();
$leerlingQuery = $db->prepare('SELECT 
          g.studentcode,
          g.geslacht,
          g.roepnaam,
          g.voorvoegsel,
          g.achternaam,
          g.geboortedatum,
          a.postcode,
          a.plaatsnaam,
          g.opleiding_start,
          g.opleiding_eind 
FROM gebruiker g 
JOIN adres a ON g.adres_id = a.id 
WHERE g.studentcode IS NOT NULL');
$leerlingQuery->execute();
$leerlingen = $leerlingQuery->fetchAll();

?>
<table>
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
        <th>Opleiding Begin</th>
        <th>Opleiding Eind</th>
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
        <th>Opleiding Begin</th>
        <th>Opleiding Eind</th>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ($leerlingen as $leerling) { ?>
        <tr>
            <td><?= $leerling[ 'studentcode' ] ?></td>
            <td><?= $leerling[ 'geslacht' ] ?></td>
            <td><?= $leerling[ 'roepnaam' ] ?></td>
            <td><?= $leerling[ 'voorvoegsel' ] ?></td>
            <td><?= $leerling[ 'achternaam' ] ?></td>
            <td><?= $leerling[ 'geboortedatum' ] ?></td>
            <td><?= $leerling[ 'postcode' ] ?></td>
            <td><?= $leerling[ 'plaatsnaam' ] ?></td>
            <td><?= $leerling[ 'opleiding_start' ] ?></td>
            <td><?= $leerling[ 'opleiding_eind' ] ?></td>
        </tr>
    <?php } ?>
    </tbody>

</table>