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
  WHERE i.evenement_id = :evenement_id
   AND aangemeld_op IS NOT NULL');
$stmt->bindParam('evenement_id',$evenement_id,PDO::PARAM_INT);
$stmt->execute();
$inschrijvingen = $stmt->fetchAll();


if (isset($_POST["toestemming"])){
    $value = $_POST["toestemming"];

    if($value = "ja"){
    $db = db("inschrijfmodule")
        $db->prepare("UPDATE inschrijving SET toestemming = 1 WHERE ?   ");
        $stmt->execute()

    }
    if($value = "nee"){
        $db = db("inschrijfmodule")
            $db->prepare("UPDATE inschrijving SET toestemming = 0 WHERE ?   ");
            $stmt->execute()
    }

}
?>

<form method = "POST" action = '<?= route("/index.php?inschrijving=overzicht&evenement_id=1"); ?>'>

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
                   <input type = "hidden" name = "leerlingid" value = "<?= $inschrijving['leerlingnummer']; ?>"/>

                   <td><?= $inschrijving['leerlingnummer'] ?></td>
                <td><?= ucfirst($inschrijving['roepnaam']) . " " . $inschrijving['tussenvoegsel'] . " " . ucfirst($inschrijving['achternaam']); ?></td>
                <td><?= date('Y-M-d H:i', strtotime($inschrijving['aangemeld_op'])) ?></td>
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
