<div class="card">
    <div class="card-header">
        <div class="pull-right">
            <a class="btn btn-primary" href="<?= route('/index.php?evenementen=alles') ?>">Terug naar evenementen</a>
        </div>
        <h4>soorten: </h4>
    </div>


    <div class="card-body">
        <div class="card-text">
            <?php
            /**
             * Created by PhpStorm.
             * User: Jonas
             * Date: 12/6/2017
             * Time: 9:38
             */

            $db = db();
            if (isset($_POST['deactiveren'])) {
                if ($_POST['deactiveren'] == 2) {
                    $stmt2 = $db->prepare("
        UPDATE soort
        SET actief = 0
        WHERE soort = '" . $_POST['soortnaam'] . "'");
                    $stmt2->execute();
                }
            }


            $stmt = $db->prepare("
SELECT soort, benodigdheid
FROM soort
WHERE actief = 1");
            $stmt->execute();

            $rows = $stmt->fetchAll();
            ?>

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
                foreach ($rows as $row) {
                    ?>
                    <tr>
                        <td><?= $row['soort'] ?></td>
                        <td><?= $row['benodigdheid'] ?></td>
                        <td><?= '<a href="' . route('/index.php?soorten=aanpassen&soort=' . $row['soort']) . '" class="btn btn-primary">Wijzig \'' . $row['soort'] . '\'</a>' ?></td>
                        <td>
                            <form name="evenementActivatie" method="post"><input type="hidden" name="deactiveren"
                                                                                 value="2"><input
                                        type="hidden" name="soortnaam" value="<?= $row['soort'] ?>">
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
