<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/11/2017
 * Time: 8:24
 */

$db = db();
$id = intval(filter_input(INPUT_GET, 'evenement_id', FILTER_SANITIZE_STRING));

if (isset($_POST['activeren'])) {
    $error = [];
    if ($_POST['activeren'] == false)
        if (!isset($_POST['comment']) || empty($_POST['comment'])) {
            $error['comment'] = ' reden is verplicht.';
        }
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    if (empty($_POST['comment'])) {
        $error['comment'] = ' het filteren van de soortnaam ging verkeerd';
    }

    $stmt = $db->prepare("
UPDATE evenement
SET comment = ?, status = ?
WHERE evenement_id = ?;
");
    $stmt->execute(array(
            $comment,
        $_POST['activeren'],
        $id
    ));

}
$stmt = $db->prepare("
SELECT evenement_id, gebruikersnaam, titel, begintijd, eindtijd, onderwerp, omschrijving, vervoer, min_leerlingen, max_leerlingen, status, locatie, lokaalnummer, soort, contactnr, comment
FROM evenement e
JOIN account a ON e.account_id = a.account_id
WHERE evenement_id = $id");

$stmt->execute();

$row = $stmt->fetch()
?>
<div class="card">
    <h4 class="card-header">Evenement activeren/deactiveren
    <div class='pull-right control-group'>
        <a href="<?= route('/index.php?evenementen=specifiek&evenement_id=' . $id) ?>"
           class="btn btn-primary">Terug naar evenementen</a>
    </div>
    </h4>
    <div class="card-body">
        <table>
            <tr>
                <td>EvenementID: </td>
                <td><?= $row['evenement_id'] ?></td>
            </tr>
            <tr>
                <td>Aangemaakt door: </td>
                <td><?= $row['gebruikersnaam'] ?></td>
            </tr>
            <tr>
                <td>Titel: </td>
                <td><?= $row['titel'] ?></td>
            </tr>
            <tr>
                <td>Onderwerp: </td>
                <td><?= $row['onderwerp'] ?></td>
            </tr>
            <tr>
                <td>Omschrijving: </td>
                <td><?= $row['omschrijving'] ?></td>
            </tr>
            <tr>
                <td>begintijd: </td>
                <td><?= $row['begintijd'] ?></td>
            </tr>
            <tr>
                <td>Eindtijd: </td>
                <td><?= $row['eindtijd'] ?></td>
            </tr>
            <tr>
                <td>minimaal aantal leerlingen: </td>
                <td><?= $row['min_leerlingen'] ?></td>
            </tr>
            <tr>
                <td>maximaal aantal leerlingen: </td>
                <td><?= $row['max_leerlingen'] ?></td>
            </tr>
            <tr>
                <td>vervoer: </td>
                <td><?= $row['vervoer'] ?></td>
            </tr>
            <tr>
                <td>Locatie: </td>
                <td><?= $row['locatie'] ?></td>
            </tr>
            <tr>
                <td>Lokaalnummer: </td>
                <td><?= $row['lokaalnummer'] ?></td>
            </tr>
            <tr>
                <td>soort: </td>
                <td><?= $row['soort'] ?></td>
            </tr>
            <tr>
                <td>contactnummer: </td>
                <td><?= $row['contactnr'] ?></td>
            </tr>
            <?php if($row['comment'] != ''){ ?>
            <tr>
                <td><span class="text-center bg-danger">Vorige deactivatie: </span></td><td><?= $row['comment']?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <div class="card-footer">
        <form name="evenementActivatie" method="post"
              action="<?php echo filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING); ?>">
            <?php
            if($row['status'] == 1){
                $actiefknop = 'danger';
                $knopnaam = 'deactiveren';
                $activeren = false ?>

            <div class="form-group">
                <label for="comment"></label>
                <textarea class="form-control" id="comment" name="comment"
                          placeholder="reden tot deactivering"
                          required="required"></textarea>
            </div>
<?php }elseif ($row['status'] == 0){
                ?><input type="hidden" name="comment" value=""><?php
                $actiefknop = 'success';
                $knopnaam = 'activeren';
                $activeren = true;
            }?>
            <input type="hidden" name="activeren" value="<?= $activeren ?>">
            <button type="submit" name="submit" class="btn btn-sm btn-<?=$actiefknop ?>"><?= $knopnaam ?></button>
        </form>
    </div>
</div>




