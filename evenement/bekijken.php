<?php
//laad database
$db = db();

$sql = "
SELECT evenement_id, titel, onderwerp, begintijd, eindtijd, s.soort, locatie, status, publiek
FROM evenement e
JOIN soort s ON e.soort_id = s.soort_id";
/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */

if ($rol === 'externbedrijf') {
    $sql .= ' AND e.account_id = :account_id';
}

//voer de query uit
$stmt = $db->prepare($sql);
/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */
if ($rol === 'externbedrijf') {
    $stmt->bindParam('account_id', $_SESSION[authenticationSessionName]);
}
$stmt->execute();

//zet resultaten van query in $rows
$rows = $stmt->fetchAll();

//check for content in results

//basis voor tabel
?>
<div class="card">
    <h4 class="card-header">Evenementen
        <span class="pull-right">
        <?php
        //beheerder en extern bedrijf mogen een evenement toevoegen
        if (in_array($rol, array('beheerder', 'externbedrijf'))) {
            print('<a href = "' . route('/index.php?evenementen=toevoegen') . '" class="btn btn-primary" ><i class="fa fa-plus" aria-hidden="true" ></i> evenement toevoegen</a>&nbsp');
        }
        //alleen de beheerder mag de soorten beheren
        if ($rol === 'beheerder') {
            print('<a href = "' . route('/index.php?soorten=overzicht') . '" class="btn btn-primary" ><i class="fa fa-pencil" aria-hidden="true"></i> soorten beheren</a>');
        }
        ?>
        </span>
    </h4>
    <div class="card-body">
        <div class="card-text">
            <?php if(count($rows) > 0) { ?>
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
                    <th class="tablehead">whitelist</th>
                </tr>
                </thead>
                <?php

                //een loop waarin per rij alle informatie in de tabel wordt gezet
                foreach ($rows as $row) {
                    //alle informatie in variabelen stoppen
                    $id = filter_var($row["evenement_id"], FILTER_SANITIZE_STRING);
                    $titel = filter_var($row["titel"], FILTER_SANITIZE_STRING);
                    $onderwerp = ucfirst(filter_var($row["onderwerp"], FILTER_SANITIZE_STRING));
                    $starttijd = date('d-M-y H:i', strtotime(filter_var($row["begintijd"], FILTER_SANITIZE_STRING)));

                    $eindtijd = "n.v.t.";
                    if ($row['eindtijd'] != 0) {
                        $eindtijd = date('d-M-y H:i', strtotime(filter_var($row["eindtijd"], FILTER_SANITIZE_STRING)));
                    }

                    $locatie = "n.v.t.";
                    if ($row["locatie"] != "") {
                        $locatie = filter_var($row["locatie"], FILTER_SANITIZE_STRING);
                    }

                    $soort = ucfirst(filter_var($row["soort"], FILTER_SANITIZE_STRING));

                    //als het evenement nog moet beginnen: actief of niet,
                    if($row['begintijd'] > date("Y-m-d H:i:s")) {
                        if ($row['status'] == false) {
                            $actief = "<td class='bg-danger'>Nee</td>";
                        } elseif ($row['status'] == true) {
                            $actief = "<td class='bg-success'>Ja</td>";
                        }
                    }

                    //als het evenement al bezig is: bezig
                    elseif($row['eindtijd'] > date("Y-m-d H:i:s")) {
                        $actief = "<td class='bg-warning text-dark'>Bezig</td>";
                    }

                    //als het evenement al afgelopen is: verlopen
                    elseif($row['eindtijd'] < date("Y-m-d H:i:s")){
                        $actief = "<td class='bg-secondary'>Verlopen</td>";
                    }

                    //publiek of privaat
                    if ($row['publiek'] == 1) {
                        $publiek = '<td class="bg-success">Publiek</td>';
                    } else {
                        $publiek = '<td class="bg-danger">Privaat</td>';
                    }

                    //Zet de informatie die zichtbaar moet zijn in de tabel
                    ?>
                    <tr>
                        <td>
                            <a href=" <?= route('/index.php?evenementen=specifiek&evenement_id=' . $id) ?> "> <?= $titel ?> </a>
                        </td>
                        <td> <?= $onderwerp ?></td>
                        <td> <?= $starttijd ?> </td>
                        <td> <?= $eindtijd ?> </td>
                        <td> <?= $locatie ?> </td>
                        <td> <?= $soort ?> </td>
                        <?= $actief ?>
                        <?= $publiek ?>
                    </tr>
                    <?php
                }
                } else {
                    //if there is no content, print following
                    print("Er zijn geen evenementen op dit moment");
                }
                ?>
            </table>
        </div>
    </div>
</div>