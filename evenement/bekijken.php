<?php
//load database
$db = db();

//take info from database/evenement
$stmt = $db->prepare("
SELECT evenement_id, titel, onderwerp, datum, soort, locatie
FROM evenement");
$stmt->execute();

//get results from query
$rows = $stmt->fetchAll();


//check for content in results
$countrow = count($rows);
if ($countrow > 0) {

    //set up base for table
    print('
        <div class="allEvents">
        <table>
            <tr>
                <th id="titel" class="tablehead">naam</th>
                <th class="tablehead">onderwerp</th>
                <th class="tablehead">datum</th>
                <th class="tablehead">plaats</th>
                <th class="tablehead">soort</th>
            </tr>
        ');
    //create a loop to get the needed info per part

    foreach ($rows as $row) {
        //put all data in variables
        htmlentities($row["evenement_id"]);
        $adres = "n.v.t.";
        $locatie = "n.v.t.";
        htmlentities($row["titel"]);
        htmlentities($row["onderwerp"]);
        htmlentities($row["datum"]);
        if ($row["locatie"] != "") {
            $locatie = htmlentities($row["locatie"]);
        }
        if ($row["soort"] != "") {
            $soort = htmlentities($row["soort"]);
        }

        //print all VISIBLE variables in the table
        print('
                <tr>
                    <td>
                       <a href="' . route('/index.php?evenementen=specifiek&evenement_id=' . $row['evenement_id']) . '">' . $row['titel'] . '</a>
                    </td>
                    <td>' . $row["onderwerp"] . '</td>
                    <td>' . $row["datum"] . '</td>
                    <td>' . $locatie . '</td>
                    <td>' . $soort . '</td>
            </tr>
            ');
    }
    print("</table></div>");
} else {
    //if there is no content, print following
    print("Er zijn geen evenementen op dit moment");
}

?>

<a href="<?= route('/index.php?evenementen=toevoegen') ?> "><i class="fa fa-plus" aria-hidden="true"></i></a>
