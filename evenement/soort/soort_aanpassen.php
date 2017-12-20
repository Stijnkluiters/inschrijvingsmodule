<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/6/2017
 * Time: 14:04
 */

//create database
$db = db();

//create a variable to catch errors
$error = [];
$soortID = filter_var(filter_input(INPUT_GET,'soortid',FILTER_SANITIZE_STRING),FILTER_VALIDATE_INT);


// check if soort exists in database, else redirect to different page.
$stmt = $db->prepare('select * from soort where soort_id = ?');
$stmt->execute(array($soortID));
if( $stmt->rowCount() === 0 )
{
    redirect('/index.php?soorten=overzicht', 'Er bestaat geen soort met dat id.');
}

if(!filter_var($soortID,FILTER_VALIDATE_INT)) {
    redirect('/index.php?soorten=overzicht', 'Soortid moet een ID zijn.');
}

if( isset($_POST[ 'submit' ]) )
{
//filter and check the content in the post variable
    /**soortnaam*/
    if( !isset($_POST[ 'soortnaam' ]) || empty($_POST[ 'soortnaam' ]) )
    {
        $error[ 'soortnaam' ] = ' soortnaam is verplicht.';
    }
    $soortnaam = filter_input(INPUT_POST, 'soortnaam', FILTER_SANITIZE_STRING);
    if( empty($soortnaam) )
    {
        $error[ 'soortnaam' ] = ' het filteren van de soortnaam ging verkeerd';
    }

    /** benodigdheden */
    $benodigdheid = filter_input(INPUT_POST, 'omschrijving', FILTER_SANITIZE_STRING);

//if there are no errors, continue with the query
    if( count($error) === 0 )
    {
        $stmt = $db->prepare('
    UPDATE `soort` SET 
    soort         = ?,
    benodigdheid  = ?
    WHERE 
    soort_id         = ?');
        //connect the variables to the information in the query
        $stmt->execute(array(
            $soortnaam,
            $benodigdheid,
            $soortID
        ));
    }
}


$stmt2 = $db->prepare('
SELECT soort, benodigdheid
FROM soort
WHERE soort_id = ?');

$stmt2->execute(array( $soortID ));

$prevalue = $stmt2->fetch();
?>
<!-- form where user can insert a 'soort' -->
<div class="card">
    <div class="card-header">
        <h4>Soort wijzigen
            <div class="pull-right">
                <a href="<?= route('/index.php?soorten=overzicht') ?>" class="btn btn-primary">terug naar
                    soortoverzicht</a>
            </div>
        </h4>
    </div>
    <form name="evenementWijzigen" method="post"
          action="<?php echo filter_var($_SERVER[ 'REQUEST_URI' ], FILTER_SANITIZE_STRING); ?>">
        <div class="col-sm-12">
            <div class="card-body">
                <div class="form-group">
                    <label for="company">Soortnaam*</label>
                    <input type="text" class="form-control" id="soortnaam" name="soortnaam" placeholder="Soortnaam"
                           value="<?= $prevalue[ 'soort' ]; ?>"/>
                </div>
                <div class="form-group">
                    <label for="omschrijving">Omschrijving</label>
                    <textarea class="form-control" id="omschrijving" name="omschrijving"
                              placeholder="Omschrijving voor het evenement"><?= $prevalue[ 'benodigdheid' ]; ?></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-sm btn-primary">Wijzigen
            </div>
        </div>
    </form>
</div>
