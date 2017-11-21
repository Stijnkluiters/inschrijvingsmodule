<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 21-11-2017
 * Time: 11:11
 */
?>
<table class="table table-bordered">
    <thead class="thead-dark">
    <tr>
        <th>Nummer</th>
        <th>Geslacht</th>
        <th>Roepnaam</th>
        <th>Voorvoegsel</th>
        <th>Achternaam</th>
        <th>Opleiding</th>
        <th>Geboortedatum</th>
        <th>Postcode en Plaats</th>
        <th>Begindatum</th>
        <th>Eindedatum</th>
        <th>Actie</th>
    </tr>
    </thead>
    <tr>
        <td>
            <?php
            $dbh = db();
            $docentenQuery = $db->prepare('SELECT gebruiker.roepnaam as naam,
            gebruiker.email as email,
            gebruiker.docent_id as docentcode,
            FROM gebruiker
            WHERE id =2');
            $docentenQuery->execute();
            $docenten = $docentenQuery->fetchAll();
            dump($docenten);
            exit;
            ?>
        </td>
    </tr>
</table>