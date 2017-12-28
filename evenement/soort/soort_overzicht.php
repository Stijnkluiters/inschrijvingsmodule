<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/6/2017
 * Time: 9:38
 */

$db = db();
if (isset($_POST['soortid'])) {

    $soortid = filter_var(filter_input(INPUT_POST,'soortid',FILTER_SANITIZE_STRING),FILTER_VALIDATE_INT);

    if (!filter_var($soortid, FILTER_VALIDATE_INT)) {
        redirect('/index.php', 'SoortID is geen nummeriek getal');
    }
    $stmt = $db->prepare('select * from soort where soort_id = ?');
    $stmt->execute(array($soortid));
    if ($stmt->rowCount() === 0) {
        redirect('/index.php', 'SoortID bestaat niet in de database');
    }
    if (isset($_POST['deactiveren'])) {
        if ($_POST['deactiveren'] == '2') {
            //if (date('Y-m-d') > date('Y-m-d', strtotime($begintijd))) {

            $stmt2 = $db->prepare("
            UPDATE soort
            SET actief = 0
            WHERE soort_id =?");
            $stmt2->execute(array($soortid));
            //}
        }
    }
}
$stmt = $db->prepare("
SELECT soort, benodigdheid, soort_id
FROM soort
WHERE actief = 1");
$stmt->execute();

$soorten = $stmt->fetchAll();
?>
<div class="card">
    <div class="card-header">
        <div class="pull-right">
            <a class="btn btn-primary" href="<?= route('/index.php?evenementen=alles') ?>">Terug naar evenementen</a>
        </div>
        <h4>soorten: </h4>
    </div>


    <div class="card-body">
        <div class="card-text">

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>naam</th>
                    <th>benodigdheden</th>
                    <th>wijzigen</th>
                    <th>Verwijderen</th>
                </tr>
                </thead>
                <?php
                foreach ($soorten as $soort) {
                    if (strlen($soort['soort']) > 25) {
                        $displaySoort = substr($soort['soort'], 0, 26) . "...";
                    } else {
                        $displaySoort = $soort['soort'];
                    }

                    ?>
                    <tr>
                        <td><?= $displaySoort ?></td>
                        <td><?= $soort['benodigdheid'] ?></td>
                        <td><?= '
                            <a href="' . route('/index.php?soorten=aanpassen&soortid=' . $soort['soort_id']) . '" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        ' ?></td>
                        <td>
                            <form name="evenementActivatie" method="post"
                                action="<?= route("/index.php?soorten=overzicht"); ?>">
                                <input type="hidden" name="deactiveren" value="2">
                                <input type="hidden" name="soortid" value="<?= $soort['soort_id'] ?>">
                                <button class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <?php
        if (1 == 1) {
            print('<a href = "' . route('/index.php?soorten=toevoegen') . '" class=" btn btn-primary" >soort toevoegen</a>');
        }
        ?>
    </div>
