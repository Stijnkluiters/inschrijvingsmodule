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
    <tr><?php
        class TableRows extends RecursiveIteratorIterator {
        function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
        }

        function current() {
        return "<td style='width: 150px; border: 1px solid black;'>" . parent::current(). "</td>";
        }

        function beginChildren() {
        echo "<tr>";
        }

        function endChildren() {
        echo "</tr>" . "\n";
    }
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inschrijfmodule";

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT id, roepnaam, achternaam FROM gebruiker");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
    }
    }
    catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    }
    $conn = null;
    echo "</table>";?>
    </tr>
</table>