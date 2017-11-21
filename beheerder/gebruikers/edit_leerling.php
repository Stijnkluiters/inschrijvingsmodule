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
<table>

    <tr><?php
        echo "<tr><th>Student</th><th>Geslacht</th><th>Roepnaam</th><th>Voorvoegsel</th><th>Achternaam</th><th>Geboortedatum</th><th>Postcode</th><th>Plaats</th><th>Opleiding Begin</th><th>Opleiding Eind</th></tr>";
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
    $stmt = $conn->prepare("SELECT g.studentcode, g.geslacht, g.roepnaam, g.voorvoegsel, g.achternaam, g.geboortedatum, a.postcode, a.plaatsnaam, g.opleiding_start, g.opleiding_eind FROM gebruiker g JOIN adres a ON g.adres_id=a.id WHERE g.studentcode IS NOT NULL");
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