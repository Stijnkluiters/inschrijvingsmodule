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
<table>
    <tr>
        <th>naam</th>
        <th>benodigdheden</th>
    </tr>
    <?php
foreach ($rows as $row){
    ?>
    <tr>
        <td><?= $row['soort']?></td>
        <td><?= $row['benodigdheid']?></td>
    </tr>
    <?php } ?>

</table>

<a href"<?php route('/index.php?soorten=toevoegen')?>">soort toevoegen</a>