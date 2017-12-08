<?php
//load database
$db = db();

//take info from database/evenement
$stmt = $db->prepare("
SELECT evenement_id, titel, onderwerp, begintijd, eindtijd, soort, locatie
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
        <table class="table table-bordered thead-dark">
        <thead>
            <tr>
                <th id="titel" class="tablehead">Evenement</th>
                <th class="tablehead">Onderwerp</th>
                <th class="tablehead">Begindatum</th>
                <th class="tablehead">Einddatum</th>
                <th class ="tablehead">Locatie</th>
                <th class="tablehead">Soort</th>
            </tr>
            </thead>
        ');
    //create a loop to get the needed info per part

    foreach ($rows as $row) {
        //put all data in variables
        filter_var($row["evenement_id"], FILTER_SANITIZE_STRING);
        $eindtijd = "n.v.t.";
        $adres = "n.v.t.";
        $locatie = "n.v.t.";
        filter_var($row["titel"], FILTER_SANITIZE_STRING);
        $onderwerp = filter_var($row["onderwerp"], FILTER_SANITIZE_STRING);
        $starttijd = filter_var($row["begintijd"], FILTER_SANITIZE_STRING);
        if ($row["locatie"] != "") {
            $locatie = filter_var($row["locatie"], FILTER_SANITIZE_STRING);
        }
        if ($row["soort"] != "") {
            $soort = filter_var($row["soort"], FILTER_SANITIZE_STRING);
        }
        if ($row['eindtijd'] != 0) {
            $eindtijd = filter_var($row["eindtijd"], FILTER_SANITIZE_STRING);
        }

        //print all VISIBLE variables in the table
        print('
                <tr class="thead-light">
                    <td>
                       <a href="' . route('/index.php?evenementen=specifiek&evenement_id=' . $row['evenement_id']) . '">' . $row['titel'] . '</a>
                    </td>
                    <td>' . $onderwerp . '</td>
                    <td>' . $starttijd . '</td>
                    <td>' . $eindtijd . '</td>
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
if (1 == 1) {
    print('<p><a href = "' . route('/index.php?evenementen=toevoegen') . '" ><i class="fa fa-plus" aria-hidden="true" ></i> evenement toevoegen</a></p>');
    print('<p><a href = "' . route('/index.php?soorten=overzicht') . '"><i class="fa fa-pencil" aria-hidden="true"></i> soorten beheren</a></p>');
}

