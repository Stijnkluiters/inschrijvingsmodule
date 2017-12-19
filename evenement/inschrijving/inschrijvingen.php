<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 13-12-2017
 * Time: 10:18
 */

$evenement_id = intval(filter_input(INPUT_GET, 'evenement_id', FILTER_SANITIZE_STRING));
if (!filter_var($evenement_id, FILTER_VALIDATE_INT)) {
    redirect('/index.php?evenement=overzicht', 'Er is wat misgegaan met de url? are you trying to hack this?');
}
$db = db();
$stmt = $db->prepare('select * from inschrijfmodule.inschrijving i
  JOIN leerling l ON i.leerlingnummer = l.leerlingnummer
  WHERE i.evenement_id = :evenement_id');
$stmt->bindParam('evenement_id',$evenement_id,PDO::PARAM_INT);
$stmt->execute();
$inschrijvingen = $stmt->fetchAll();


if (isset($_POST["toestemming"])){
    $value = $_POST["toestemming"];
    $leerlingnummer = $_POST["leerlingnummer"];
    $toestemming = 0;

    if($value == "ja"){
        $toestemming = 1;
    } elseif($value == "nee") {
        $toestemming = 0;
    }
        $stmt = $db->prepare("UPDATE inschrijving SET toestemming = ? WHERE leerlingnummer = ? AND evenement_id = ?");
        $stmt->execute(array($toestemming,$leerlingnummer, $evenement_id));
}
?>

<form method = "POST" action = '<?= route("/index.php?inschrijving=overzicht&evenement_id=".$evenement_id); ?>'>

    <table id="dataTable" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Leerlingnummer</th>
            <th>Naam</th>
            <th>Aangemeld op</th>
            <th>Gewhitelist</th>
            <th>Toestemming</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Leerlingnummer</th>
            <th>Naam</th>
            <th>Aangemeld op</th>
            <th>Gewhitelist</th>
            <th>Toestemming</th>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($inschrijvingen as $inschrijving) { ?>
               <tr>
                   <input type = "hidden" name = "leerlingnummer" value = "<?= $inschrijving['leerlingnummer']; ?>"/>

                   <td><?= $inschrijving['leerlingnummer'] ?></td>
                <td><?= ucfirst($inschrijving['roepnaam']) . " " . $inschrijving['tussenvoegsel'] . " " . ucfirst($inschrijving['achternaam']); ?></td>
                <td><?= (!empty($inschrijving['aangemeld_op'])) ? date('Y-M-d H:i', strtotime($inschrijving['aangemeld_op'])) : '<i class="fa fa-times" aria-hidden="true"></i>' ?></td>
                <td><?= ($inschrijving['gewhitelist'] == 1) ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-times" aria-hidden="true"></i>'; ?></td>
                <td>
                    <?= '<button type="submit" name = "toestemming" value = "ja" class="btn btn-success">Ja</button>'; ?>
                    <?= '<button type="submit" name = "toestemming" value = "nee" class="btn btn-danger">Nee</button>'; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
</form>

