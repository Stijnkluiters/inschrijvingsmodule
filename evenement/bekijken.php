<?php
//load database
$db = db();

//take info from database/evenement
$stmt = $db->prepare("
SELECT e.id, e.titel, es.onderwerp, e.datum, e.adres_id, e.locatie_id 
FROM evenement e JOIN evenement_soort es 
ON evenement_soort_id = es.id 
LEFT JOIN locatie l ON l.id = e.locatie_id 
LEFT JOIN adres a ON a.id = e.adres_id");
$stmt->execute();

//get results from query
$rows = $stmt->fetchAll();

//check for content in results
$countrow = count($rows);
if (count($countrow) > 0) {

    //set up base for table
    print('
        <div class="allEvents">
        <table>
            <tr>
                <th id="titel" class="tablehead">naam</th>
                <th class="tablehead">onderwerp</th>
                <th class="tablehead">datum</th>
                <th class="tablehead">plaats</th>
                <th class="tablehead">locatie</th>
            </tr>
        ');
    //create a loop to get the needed info per part

    foreach ($rows as $row) {
        //put all data in variables
        htmlentities($row["id"]);
        $adres = "n.v.t.";
        $locatie = "n.v.t.";
        htmlentities($row["titel"]);
        htmlentities($row["onderwerp"]);
        htmlentities($row["datum"]);
        if ($row["adres_id"] != "") {
            $adres = htmlentities($row["adres_id"]);
        }
        if ($row["locatie_id"] != "") {
            $locatie = htmlentities($row["locatie_id"]);
        }

        //print all VISIBLE variable in the table
        print('
                <tr>
                    <td>
                       <a href="' . route('index.php?evenementen=specifiek&evenement_id=' . $row['id']) . '">' . $row['titel'] . '</a>
                    </td>
                    <td>' . $row["onderwerp"] . '</td>
                    <td>' . $row["datum"] . '</td>
                    <td>' . $adres . '</td>
                    <td>' . $locatie . '</td>
            </tr>
            ');
    }
    print("</table></div>");
} else {
    //if there is no content, print following
    print("Er zijn geen evenementen op dit moment");
}

?>

