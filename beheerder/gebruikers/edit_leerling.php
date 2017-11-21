<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 21-11-2017
 * Time: 11:11
 */
$db = db();
$docentenQuery = $db->prepare('SELECT gebruiker.roepnaam as naam FROM gebruiker');
$docentenQuery->execute();
$docenten = $docentenQuery->fetchAll();

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

            ?>
        </td>
    </tr>
</table>