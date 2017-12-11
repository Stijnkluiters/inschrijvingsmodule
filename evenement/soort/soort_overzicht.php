<h4>soorten: </h4>
<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/6/2017
 * Time: 9:38
 */

$db = db();

$stmt = $db->prepare("
SELECT soort, benodigdheid
FROM soort");
$stmt->execute();

$rows = $stmt->fetchAll();
?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>naam</th>
        <th>benodigdheden</th>
        <th>wijzigen</th>
    </tr>
    </thead>
    <?php
foreach ($rows as $row){
    ?>
    <tr>
        <td><?= $row['soort']?></td>
        <td><?= $row['benodigdheid']?></td>
        <td><?='<a href="' . route('/index.php?soorten=aanpassen&soort=' . $row['soort']) . '" class="btn btn-primary">' . $row['soort'] . ' wijzigen</a>'?></td>
    </tr>
    <?php } ?>
</table>
<?php
if(1==1) {
print('<a href = "' . route('/index.php?soorten=toevoegen') . '" class="btn btn-primary" >soort toevoegen</a>');
}
?>