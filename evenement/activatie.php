<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/11/2017
 * Time: 8:24
 */
if ($rol !== 'beheerder' && $rol !== 'externbedrijf') {
    redirect('/index.php', 'Access denied :)');
}

$db = db();
$id = filter_var(filter_input(INPUT_GET, 'evenement_id', FILTER_SANITIZE_STRING), FILTER_VALIDATE_INT);

//als de activatie/deactivatie-knop is aangeklikt, kijk of die 1 of 0 is en filter de input.
if (isset($_POST['activeren']) && ($_POST['activeren'] === '1' || $_POST['activeren'] === '0')) {
    $error = [];


    if ($_POST['activeren'] === '0') {
        //kijk of 'comment' bestaat, zoniet, geef een error
        if (!isset($_POST['comment']) || empty($_POST['comment'])) {
            $error['comment'] = ' reden is verplicht.';
        }
        //filter de reden, als er niks (meer) instaat, geef een filtererror
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        if (empty($comment)) {
            $error['comment'] = ' het filteren van de reden ging verkeerd';
        }
    } else{
        $comment = '';
    }


    //verzekering dat activeren een getal is.
    $activeren = filter_input(INPUT_POST, 'activeren', FILTER_SANITIZE_NUMBER_INT);

    //als er geen errors zijn, ga door met de actie
    if (count($error) === 0) {
        //statement klaarmaken en uitvoeren
        $stmt = $db->prepare("
        UPDATE evenement
        SET comment = ?, status = ?
        WHERE evenement_id = ?;
    ");
        $stmt->execute(array(
            $comment,
            $activeren,
            $id
        ));

    }
}
//haal benodigde rijen uit de database met behulp van dbo/sql
$sql = "
    SELECT evenement_id, gebruikersnaam, titel, begintijd, eindtijd, onderwerp, omschrijving, vervoer, min_leerlingen, max_leerlingen, status, locatie, lokaalnummer, s.soort, contactnr, comment
    FROM evenement e
    JOIN soort s ON e.soort_id=s.soort_id
    JOIN account a ON e.account_id = a.account_id
    WHERE e.evenement_id = :evenement_id";
/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */
if ($rol === 'externcontact') {
    $sql .= ' AND e.account_id = :account_id';
}


$stmt = $db->prepare($sql);
$stmt->bindParam('evenement_id', $id);
/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */
if ($rol === 'externcontact') {
    $stmt->bindParam('account_id', $_SESSION[authenticationSessionName]);
}

$stmt->execute();

//rij uit result halen
$row = $stmt->fetch()
?>
<!--laat alle informatie zien -->
<div class="card">
    <div class="card-header">
        <strong>Evenement</strong>
        <small>Activeren/Deactiveren</small>
        <div class='pull-right control-group'>
            <a href="<?= route('/index.php?evenementen=specifiek&evenement_id=' . $id) ?>"
               class="btn btn-primary">Terug naar evenementen</a>
        </div>
    </div>
    <div class="card-body">
        <table>
            <tr>
                <td>EvenementID:</td>
                <td><?= $row['evenement_id'] ?></td>
            </tr>
            <tr>
                <td>Aangemaakt door:</td>
                <td><?= $row['gebruikersnaam'] ?></td>
            </tr>
            <tr>
                <td>Titel:</td>
                <td><?= $row['titel'] ?></td>
            </tr>
            <tr>
                <td>Onderwerp:</td>
                <td><?= $row['onderwerp'] ?></td>
            </tr>
            <tr>
                <td>Omschrijving:</td>
                <td><?= $row['omschrijving'] ?></td>
            </tr>
            <tr>
                <td>begintijd:</td>
                <td><?= $row['begintijd'] ?></td>
            </tr>
            <tr>
                <td>Eindtijd:</td>
                <td><?= $row['eindtijd'] ?></td>
            </tr>
            <tr>
                <td>minimaal aantal leerlingen:</td>
                <td><?= $row['min_leerlingen'] ?></td>
            </tr>
            <tr>
                <td>maximaal aantal leerlingen:</td>
                <td><?= $row['max_leerlingen'] ?></td>
            </tr>
            <tr>
                <td>vervoer:</td>
                <td><?= $row['vervoer'] ?></td>
            </tr>
            <tr>
                <td>Locatie:</td>
                <td><?= $row['locatie'] ?></td>
            </tr>
            <tr>
                <td>Lokaalnummer:</td>
                <td><?= $row['lokaalnummer'] ?></td>
            </tr>
            <tr>
                <td>soort:</td>
                <td><?= $row['soort'] ?></td>
            </tr>
            <tr>
                <td>contactnummer:</td>
                <td><?= $row['contactnr'] ?></td>
            </tr>
            <!--Alleen als er al een reden tot deactivatie van dit evenement is laat het systeem de reden zien -->
            <?php if ($row['comment'] != '') { ?>
                <tr>
                    <td><span class="text-center bg-danger">Reden deactivatie: </span></td>
                    <td><?= $row['comment'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="card-footer">
        <form name="evenementActivatie" method="post"
              action="<?= filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING); ?>">
            <?php
            if($row['begintijd'] > date("Y-m-d H:i:s")) {

                //als het evenement actief is, komt er een (rode)deactivatieknop waarbij een reden moet worden ingevoerd.
                if ($row['status'] == 1) {
                    $actiefknop = 'btn-danger';
                    $knopnaam = 'deactiveren';
                    $activeren = '0' ?>

                    <div class="form-group">
                        <label for="comment"></label>
                        <textarea class="form-control" id="comment" name="comment"
                                  placeholder="reden tot deactivering"
                                  required="required"></textarea>
                    </div>
                <?php } //als het evenement niet actief is en het account van de beheerder is, komt er een groene activatieknop
                elseif ($row['status'] == 0 && $rol === 'beheerder') {
                    $actiefknop = 'btn-success';
                    $knopnaam = 'activeren';
                    $activeren = '1';
                } //als een een account van een externbedrijf de pagina bezoekt en het evenement is niet actief, laat het systeem een grijze knop zien die niks doet
                elseif ($row['status'] == 0 && $rol === 'externbedrijf') {
                    $actiefknop = '';
                    $knopnaam = 'activeren';
                    $activeren = '';
                }
            }
            else{
                $activeren = '';
                $actiefknop = '';
                $knopnaam = 'Verander de begin- (en eind-) datum om de status van dit evenement te veranderen';
            }?>
            <input type="hidden" name="activeren" value="<?= $activeren ?>">
            <button type="submit" name="submit" class="btn btn-sm <?= $actiefknop ?>"><?= $knopnaam ?></button>
        </form>
    </div>
</div>




