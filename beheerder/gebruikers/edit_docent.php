<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 21/11/2017
 * Time: 12:22
 */

/**Select * from gebruiker where afkorting is not null*/

$db = db();
$docentenQuery = $db->prepare('SELECT afkorting, roepnaam, voorvoegsel, achternaam, geslacht, geboortedatum  FROM gebruiker WHERE afkorting IS NOT NULL');
$docentenQuery->execute();
$docenten = $docentenQuery->fetchAll();

?>
<table class="table table-bordered">
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
            $stmt = $conn->prepare("SELECT afkorting, roepnaam, voorvoegsel, achternaam, geslacht, geboortedatum  FROM gebruiker WHERE afkorting IS NOT NULL");
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


