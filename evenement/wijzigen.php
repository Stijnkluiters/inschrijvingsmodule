<?php

/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 11/24/2017
 * Time: 15:44
 */
$db = db();
//Get the id that's been given from specifiek.php
$id = intval(filter_input(INPUT_GET, 'evenement_id', FILTER_SANITIZE_STRING));
if( !filter_var($id, FILTER_VALIDATE_INT) )
{
    echo "evenement_id is geen numerieke waarde";
    exit;
}
if( isset($_POST[ 'titel' ]) )
{
    $error = [];

    /**titel*/
    if( !isset($_POST[ 'titel' ]) || empty($_POST[ 'titel' ]) )
    {
        $error[ 'titel' ] = ' titel is verplicht.';
    }
    $titel = filter_input(INPUT_POST, 'titel', FILTER_SANITIZE_STRING);
    if( empty($titel) )
    {
        $error[ 'titel' ] = ' het filteren van titel ging verkeerd';
    }

    /** starttijd */
    if( !isset($_POST[ 'starttijd' ]) || empty($_POST[ 'starttijd' ]) )
    {
        $error[ 'starttijd' ] = ' starttijd is verplicht';
    }
    $starttijd = filter_input(INPUT_POST, 'starttijd', FILTER_SANITIZE_STRING);
    if( empty($starttijd) )
    {
        $error[ 'starttijd' ] = ' het filteren van starttijd ging verkeerd';
    }
    /** eindtijd */
    if( !isset($_POST[ 'eindtijd' ]) || empty($_POST[ 'eindtijd' ]) )
    {
        $error[ 'eindtijd' ] = ' eindtijd is verplicht';
    }
    $eindtijd = filter_input(INPUT_POST, 'eindtijd', FILTER_SANITIZE_STRING);
    if( empty($eindtijd) )
    {
        $error[ 'eindtijd' ] = ' het filteren van eindtijd ging verkeerd';
    }

    /** onderwerp */
    if( !isset($_POST[ 'onderwerp' ]) || empty($_POST[ 'onderwerp' ]) )
    {
        $error[ 'onderwerp' ] = ' onderwerp is verplicht';
    }
    $onderwerp = filter_input(INPUT_POST, 'onderwerp', FILTER_SANITIZE_STRING);
    if( empty($onderwerp) )
    {
        $error[ 'onderwerp' ] = ' het filteren van onderwerp ging verkeerd';
    }

    /** omschrijving */
    if( !isset($_POST[ 'omschrijving' ]) || empty($_POST[ 'omschrijving' ]) )
    {
        $error[ 'omschrijving' ] = ' omschrijving is verplicht';
    }
    $omschrijving = filter_input(INPUT_POST, 'omschrijving', FILTER_SANITIZE_STRING);
    if( empty($omschrijving) )
    {
        $error[ 'omschrijving' ] = ' het filteren van omschrijving ging verkeerd';
    }



    /** locatie */
    if( !isset($_POST[ 'locatie' ]) || empty($_POST[ 'locatie' ]) )
    {
        $error[ 'locatie' ] = ' locatie is verplicht';
    }
    $locatie = filter_input(INPUT_POST, 'locatie', FILTER_SANITIZE_STRING);
    if( empty($locatie) )
    {
        $error[ 'locatie' ] = ' het filteren van locatie ging verkeerd';
    }

    /** locatie */
    if( !isset($_POST[ 'locatie' ]) || empty($_POST[ 'locatie' ]) )
    {
        $error[ 'locatie' ] = ' locatie is verplicht';
    }
    $locatie = filter_input(INPUT_POST, 'locatie', FILTER_SANITIZE_STRING);
    if( empty($locatie) )
    {
        $error[ 'locatie' ] = ' het filteren van locatie ging verkeerd';
    }

    /** Soort */
    if( !isset($_POST[ 'soort' ]) || empty($_POST[ 'soort' ]) )
    {
        $error[ 'soort' ] = ' soort is verplicht';
    }
    $soort = filter_input(INPUT_POST, 'soort', FILTER_SANITIZE_STRING);
    if( empty($soort) )
    {
        $error[ 'soort' ] = ' het filteren van soort ging verkeerd';
    }

    // not required fields here but preveent XSS attack
    /** Vervoer */
    $vervoer = filter_input(INPUT_POST, 'vervoer', FILTER_SANITIZE_STRING);
    if( $vervoer === false )
    {
        $error[ 'vervoer' ] = ' het filteren van vervoer ging verkeerd';
    }

    /** Min_leerlingen */
    $min_leerlingen = filter_input(INPUT_POST, 'min_leerlingen', FILTER_SANITIZE_STRING);
    if( $min_leerlingen === false )
    {
        $error[ 'Min_leerlingen' ] = ' het filteren van Minimaal aantal leerlingen ging verkeerd';
    }

    /** Max_leerlingen */
    $max_leerlingen = filter_input(INPUT_POST, 'max_leerlingen', FILTER_SANITIZE_STRING);
    if( $max_leerlingen === false )
    {
        $error[ 'Max_leerlingen' ] = ' het filteren van Maximaal aantal leerlingen ging verkeerd';
    }


    /** Lokaalnummer */
    $lokaalnummer = filter_input(INPUT_POST, 'lokaalnummer', FILTER_SANITIZE_STRING);
    if( $lokaalnummer === false )
    {
        $error[ 'lokaalnummer' ] = ' het filteren van lokaalnummer ging verkeerd';
    }
    /** Contactnummer */

    $contactnummer = filter_input(INPUT_POST, 'contactnummer', FILTER_SANITIZE_STRING);
    if( $contactnummer === false )
    {
        $error[ 'contactnummer' ] = ' het filteren van contact ging verkeerd';
    }


    if(count($error) === 0) {
    $stmt = $db->prepare('
        UPDATE `evenement` SET 
        `titel`=?,
        `begintijd`=?,
        `eindtijd`=?,
        `onderwerp`=?,
        `locatie`=?,
        `omschrijving`=?,
        `vervoer`=?,
        `min_leerlingen`=?,
        `max_leerlingen`=?,
        `lokaalnummer`=?,
        `soort`=?,
        `contactnr`= ?
        WHERE 
        `evenement_id`= ?');
    $stmt->execute(array(
        $titel,
        $starttijd,
        $eindtijd,
        $onderwerp,
        $locatie,
        $omschrijving,
        $vervoer,
        $min_leerlingen,
        $max_leerlingen,
        $lokaalnummer,
        $soort,
        $contactnummer,
        $id
    ));

    $stmt->execute();
    }

}


?>
<!--<a href=" <?php //route('/index.php?evenementen=specifiek&evenement_id=' . $id) ?>" >terug naar evenement bekijken</a>-->
<?php

//load info from database using the id

$stmt = $db->prepare("
SELECT e.titel as titel, e.onderwerp, e.omschrijving, e.locatie, e.lokaalnummer, e.begintijd, e.eindtijd, e.vervoer, e.min_leerlingen, e.max_leerlingen, e.soort, e.contactnr
FROM evenement e 
JOIN soort s ON s.soort = e.soort
WHERE evenement_id = $id");
$stmt->execute();

//put the results in $row
$row = $stmt->fetch();

$soorten = $db->query('select * from soort WHERE soort.soort IS NOT NULL');
$soorten = $soorten->fetchAll(PDO::FETCH_ASSOC);

?>
<form name="evenementWijzigen" method="post"
      action="<?php echo filter_var($_SERVER[ 'REQUEST_URI' ], FILTER_SANITIZE_STRING); ?>">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header">
                <strong>Evenement</strong>
                <small>Wijzigen</small>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="company">Titel*</label>
                    <input type="text" class="form-control" id="titel" name="titel" placeholder="Evenementtitel"
                           value="<?= $row[ 'titel' ]; ?>"/>
                </div>

                <div class="form-group">
                    <label for="starttijd">Begin datum en tijd*</label>
                    <input type="datetime-local" class="form-control" required="required" id="starttijd"
                           name="starttijd" placeholder="Starttijd"
                           value="<?= date('Y-m-d\TH:i', strtotime($row[ 'begintijd' ])); ?>"/>
                </div>

                <div class="form-group">
                    <label for="company">Begin datum en tijd*</label>
                    <input type="datetime-local" class="form-control" id="eindtijd" name="eindtijd"
                           placeholder="Eindtijd" value="<?= date('Y-m-d\TH:i', strtotime($row[ 'eindtijd' ])); ?>"/>
                </div>

                <div class="form-group">
                    <label for="onderwerp">Onderwerp*</label>
                    <input type="text" class="form-control" id="onderwerp" name="onderwerp" placeholder="Onderwerp"
                           value="<?= $row[ 'onderwerp' ]; ?>"/>
                </div>

                <div class="form-group">
                    <label for="omschrijving">Omschrijving*</label>
                    <textarea class="form-control" id="omschrijving" name="omschrijving"
                              placeholder="Omschrijving voor het evenement"
                              required="required"><?= $row[ 'omschrijving' ]; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="vervoer">Vervoer</label>
                    <input type="text" class="form-control" id="vervoer" name="vervoer"
                           value="<?= $row['vervoer']; ?>"
                           placeholder="vervoer">
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="min_leerlingen">Minimaal aantal leerlingen</label>
                        <input type="number" class="form-control" id="min_leerlingen" name="min_leerlingen"
                               value="<?= $row['min_leerlingen']; ?>"
                               placeholder="Minimaal aantal leerlingen"/>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="max_leerlingen">Maximaal aantal leerlingen</label>
                        <input type="number" class="form-control" id="max_leerlingen" name="max_leerlingen"
                               value="<?= $row['max_leerlingen']; ?>"
                               placeholder="Maximaal aantal leerlingen"/>
                    </div>

                </div>
                <div class="form-group">
                    <label for="locatie">Locatie</label>
                    <input type="text" class="form-control" id="locatie" name="locatie" value="<?= $row['locatie'];?>" placeholder="Locatie">
                </div>
                <div class="form-group">
                    <label for="lokaalnummer">Lokaalnummer ( indien van toepassing )</label>
                    <input type="text" class="form-control" name='lokaalnummer' id="lokaalnummer"
                           value="<?= $row['lokaalnummer'] ?>"
                           placeholder="lokaalnummer">
                </div>
                <div class="form-group">
                    <label for="soort">Soort</label>
                    <select class="form-control" id="soort" name="soort">
                        <option value="">Seleceer uw soort</option>
                        <?php

                        foreach ($soorten as $key => $soort)
                        {
                            if( !empty($soort['soort']) )
                            {

                                echo '<option value="' .
                                $soort[ 'soort' ] .
                                    '">' .
                                    $soort[ 'soort' ] .
                                    '<option>';
                            }
                        }
                        ?>
                    </select>
                </div>

            </div>

            <?php

            if(isset($error)) {
                print '<ul>';
                foreach ($error as $key => $message) {
                    print '<li>'.$key.':'.$message.'</li>';
                }
                print '</ul>';
            }

            ?>
            <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-sm btn-primary">Aanpassen</button>
            </div>
        </div>

    </div>
</form>
