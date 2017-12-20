<?php
//load database
$db = db();

$sql = "
SELECT evenement_id, titel, onderwerp, begintijd, eindtijd, s.soort, locatie, status
FROM evenement e
JOIN soort s ON e.soort_id = s.soort_id
WHERE s.actief = 1";
/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */

if ($rol === 'externbedrijf') {
    $sql .= ' AND e.account_id = :account_id';
}

//take info from database/evenement
$stmt = $db->prepare($sql);
/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */
if ($rol === 'externbedrijf') {
    $stmt->bindParam('account_id', $_SESSION[authenticationSessionName]);
}
$stmt->execute();

//get results from query
$rows = $stmt->fetchAll();

//check for content in results
if (count($rows) > 0) {

//set up base for table
?>
<div class="card">
    <h4 class="card-header">Evenementen</h4>
    <div class="card-body">
        <div class="card-text">
            <table class="table table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th id="titel" class="tablehead">Evenement</th>
                    <th class="tablehead">Onderwerp</th>
                    <th class="tablehead">Begindatum</th>
                    <th class="tablehead">Einddatum</th>
                    <th class="tablehead">Locatie</th>
                    <th class="tablehead">Soort</th>
                    <th class="tablehead">Actief</th>
                </tr>
                </thead>
                <?php
                //create a loop to get the needed info per part

                foreach ($rows as $row) {
                    //put all data in variables
                    $eindtijd = "n.v.t.";
                    $adres = "n.v.t.";
                    $locatie = "n.v.t.";
                    $onderwerp = filter_var($row["onderwerp"], FILTER_SANITIZE_STRING);
                    $starttijd = date('d-M-y H:i', strtotime(filter_var($row["begintijd"], FILTER_SANITIZE_STRING)));
                    if ($row['status'] == false) {
                        $actief = "<td class='bg-danger'><span>Nee</span></td>";
                    } elseif ($row['status'] == true) {
                        $actief = "<td class='bg-success'><span>Ja</span></td>";
                    }
                    if ($row["locatie"] != "") {
                        $locatie = filter_var($row["locatie"], FILTER_SANITIZE_STRING);
                    }
                    if ($row["soort"] != "") {
                        $soort = filter_var($row["soort"], FILTER_SANITIZE_STRING);
                    }
                    if ($row['eindtijd'] != 0) {
                        $eindtijd = date('d-M-y H:i', strtotime(filter_var($row["eindtijd"], FILTER_SANITIZE_STRING)));
                    }

                    //print all VISIBLE variables in the table
                    ?>
                    <tr>
                        <td>
                            <a href=" <?= route('/index.php?evenementen=specifiek&evenement_id=' . $row['evenement_id']) ?> "> <?= $row['titel'] ?> </a>
                        </td>
                        <td> <?= $onderwerp ?></td>
                        <td> <?= $starttijd ?> </td>
                        <td> <?= $eindtijd ?> </td>
                        <td> <?= $locatie ?> </td>
                        <td> <?= $soort ?> </td>
                        <?= $actief ?>
                    </tr>
                    <?php
                } ?>
            </table>
        </div>

        <?php

        } else {
            //if there is no content, print following
            print("Er zijn geen evenementen op dit moment");
        }
        if(in_array($rol,array('beheerder','externbedrijf'))) {
        print('<p><a href = "' . route('/index.php?evenementen=toevoegen') . '" class="btn btn-primary" ><i class="fa fa-plus" aria-hidden="true" ></i> evenement toevoegen</a></p>');
        }
        if ($rol === 'beheerder') {
            print('<p><a href = "' . route('/index.php?soorten=overzicht') . '" class="btn btn-primary" ><i class="fa fa-pencil" aria-hidden="true"></i> soorten beheren</a></p>');
        }
        ?>
    </div>
</div>