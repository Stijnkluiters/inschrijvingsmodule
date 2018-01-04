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

$stmt = $db->prepare('select * from evenement where evenement_id = :evenement_id');
$stmt->bindParam('evenement_id', $id);
$stmt->execute();
if( $stmt->rowCount() == 0 )
{
    redirect('/index.php', 'evenement niet gevonden in de database');
}

/** $rol Rol wordt gedefineerd in de index, onder de Evenementen $_GET. */
if( $rol === 'externbedrijf' )
{
    viewEvent($stmt->fetch());
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
    /**
     * controleren of de starttijd wel een tijd is
     */
    if( strtotime($starttijd) === false )
    {
        $error[ 'starttijd' ] = 'starttijd is geen datum';
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

    /**
     * controleren of de starttijd vroeger is dan de eindtijd, en hoger is dan de huidige tijd
     */
    if( !empty($eindtijd) && !empty($starttijd) )
    {
        $starttijd = date('Y-m-d H:i', strtotime($starttijd));
        $eindtijd = date('Y-m-d H:i', strtotime($eindtijd));
        $huidigetijd = date('Y-m-d H:i', strtotime("now"));

        // controleer of de startijd vroeger is dan de eindtijd
        if( $starttijd >= $eindtijd )
        {
            $error[ 'starttijd' ] = ' starttijd moet eerder zijn dan de eindtijd.';
        }
        // controleer of de starttijd later dan nu is.
        if( $starttijd < $huidigetijd )
        {
            $error[ 'starttijd' ] = ' starttijd moet later zijn dan de huidige tijd.';
        }

        /**
         * controleren of de eindtijd later is dan de huidige tijd.
         */
        if( $eindtijd <= $starttijd )
        {
            $error[ 'eindtijd' ] = ' eindtijd moet later zijn dan de start tijd.';
        }
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


    /** Whitelist */
    if( !isset($_POST[ 'whitelist' ]) )
    {
        $error[ 'soort' ] = ' whitelist is verplicht';
    }
    $whitelist = filter_input(INPUT_POST, 'whitelist', FILTER_SANITIZE_NUMBER_INT);
    if( $whitelist !== '1' && $whitelist !== '0' )
    {
        $error[ 'soort' ] = ' whitelist kan alleen maar publiek of privaat zijn';
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

    $contactnr = filter_input(INPUT_POST, 'contactnr', FILTER_SANITIZE_NUMBER_INT);
    if( strlen($contactnr) > 11 )
    {
        $error[ 'contactnummer' ] = ' het contactnummer mag niet langer zijn dan 11 karakters';
    }
    if( $contactnr === false )
    {
        $error[ 'contactnummer' ] = ' het filteren van contact ging verkeerd';
    }

    $inschrijving = $db->prepare("
    SELECT publiek
    FROM evenement
    WHERE evenement_id=?");
    $inschrijving->execute(array( $id ));
    $whitelistcheck = $inschrijving->fetch();

    if( count($error) === 0 )
    {
        $update = $db->prepare('
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
        `soort_id`=?,
        `contactnr`=?,
        `publiek`=?
        WHERE 
        `evenement_id`=?');

        $update->execute(array(
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
            $contactnr,
            $whitelist,
            $id
        ));


        linkStudentstoEvents(intval($id), null, intval($whitelist));

        redirect('/index.php?evenementen=specifiek&evenement_id=' . $id, 'Evenement gewijzigd');

    }
}

//load info from database using the id

$stmt = $db->prepare("
SELECT e.titel as titel,
 e.onderwerp, 
 e.omschrijving, 
 e.locatie, e.lokaalnummer, e.begintijd, e.eindtijd, e.vervoer, e.min_leerlingen, e.max_leerlingen, s.soort, e.soort_id, contactnr, publiek
FROM evenement e 
JOIN soort s ON s.soort_id = e.soort_id
WHERE evenement_id = $id");
$stmt->execute();

//put the results in $row
$row = $stmt->fetch();

$soorten = $db->query('select * from soort WHERE soort.soort_id IS NOT NULL AND actief = 1');
$soorten = $soorten->fetchAll(PDO::FETCH_ASSOC);

?>
<form name="evenementToevoegen" method="post"
      action="<?php echo filter_var($_SERVER[ 'REQUEST_URI' ], FILTER_SANITIZE_STRING); ?>">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <strong>Evenement</strong>
                <small>Wijzigen</small>
                <div class='pull-right control-group'>
                    <a href="<?= route('/index.php?evenementen=specifiek&evenement_id=' . $id) ?>"
                       class="btn btn-primary">Terug naar evenement</a>
                </div>
            </div>
            <div class="card-body">

                <p>
                    <strong>Verplichte velden *</strong>
                </p>

                <!-- Titel form -->
                <div class="form-group">
                    <label for="titel">Titel *</label>
                    <input type="text"
                           class="form-control <?= (isset($error[ 'titel' ])) ? 'is-invalid' : ''; ?>"
                           id="titel"
                           name="titel"
                           required="required"
                           placeholder="Evenementtitel"
                           value="<?= (isset($_POST[ 'titel' ])) ? $_POST[ 'titel' ] : $row[ 'titel' ]; ?>"
                    />
                    <?php if( isset($error[ 'titel' ]) ) { ?>
                        <!-- Titel helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'titel' ]; ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- starttijd form -->
                <div class="form-group">
                    <label for="starttijd">Begindatum en tijd *</label>
                    <input type="datetime-local"
                           class="form-control <?= (isset($error[ 'starttijd' ])) ? 'is-invalid' : ''; ?>"
                           id="starttijd"
                           name="starttijd"
                           required="required"
                           placeholder="Begindatum en tijd"
                           value="<?= (isset($_POST[ 'starttijd' ])) ? $_POST[ 'starttijd' ] : date("Y-m-d\TH:i:s", strtotime($row[ 'begintijd' ])); ?>"
                    />
                    <?php if( isset($error[ 'starttijd' ]) ) { ?>
                        <!-- starttijd helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'starttijd' ]; ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- eindtijd form -->
                <div class="form-group">
                    <label for="eindtijd">Einddatum en tijd *</label>
                    <input type="datetime-local"
                           class="form-control <?= (isset($error[ 'eindtijd' ])) ? 'is-invalid' : ''; ?>"
                           id="eindtijd"
                           name="eindtijd"
                           required="required"
                           placeholder="Einddatum en tijd"
                           value="<?= (isset($_POST[ 'eindtijd' ])) ? $_POST[ 'eindtijd' ] : date("Y-m-d\TH:i:s", strtotime($row[ 'eindtijd' ])); ?>"
                    />
                    <?php if( isset($error[ 'eindtijd' ]) ) { ?>
                        <!-- eindtijd helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'eindtijd' ]; ?>
                        </div>
                    <?php } ?>
                </div>


                <!-- onderwerp form -->
                <div class="form-group">
                    <label for="onderwerp">Onderwerp *</label>
                    <input type="text"
                           class="form-control <?= (isset($error[ 'onderwerp' ])) ? 'is-invalid' : ''; ?>"
                           id="onderwerp"
                           name="onderwerp"
                           required="required"
                           placeholder="Onderwerp"
                           value="<?= (isset($_POST[ 'onderwerp' ])) ? $_POST[ 'onderwerp' ] : $row[ 'onderwerp' ]; ?>"
                    />
                    <?php if( isset($error[ 'onderwerp' ]) ) { ?>
                        <!-- onderwerp helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'onderwerp' ]; ?>
                        </div>
                    <?php } ?>
                </div>


                <!-- omschrijving textarea form -->
                <div class="form-group">
                    <label for="omschrijving">Omschrijving *</label>
                    <textarea class="form-control <?= (isset($error[ 'omschrijving' ])) ? 'is-invalid' : ''; ?>"
                              id="omschrijving"
                              name="omschrijving"
                              placeholder="Omschrijving voor het evenement"
                              required="required"
                              rows="3"
                    ><?= (isset($_POST[ 'omschrijving' ])) ? $_POST[ 'omschrijving' ] : $row[ 'omschrijving' ]; ?></textarea>

                    <?php if( isset($error[ 'omschrijving' ]) ) { ?>
                        <!-- omschrijving textarea helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'omschrijving' ]; ?>
                        </div>
                    <?php } ?>
                </div>


                <div class="row">

                    <!-- min_leerlingen form -->
                    <div class="form-group col-sm-6">
                        <label for="min_leerlingen">Minimaal aantal leerlingen *</label>
                        <input type="number"
                               class="form-control <?= (isset($error[ 'min_leerlingen' ])) ? 'is-invalid' : ''; ?>"
                               id="min_leerlingen"
                               name="min_leerlingen"
                               required="required"
                               placeholder="Minimaal aantal leerlingen"
                               value="<?= (isset($_POST[ 'min_leerlingen' ])) ? $_POST[ 'min_leerlingen' ] : $row[ 'min_leerlingen' ]; ?>"
                        />
                        <?php if( isset($error[ 'min_leerlingen' ]) ) { ?>
                            <!-- min_leerlingen helper -->
                            <div class="invalid-feedback">
                                <?= $error[ 'min_leerlingen' ]; ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- max_leerlingen form -->
                    <div class="form-group col-sm-6">
                        <label for="max_leerlingen">Maximaal aantal leerlingen *</label>
                        <input type="number"
                               class="form-control <?= (isset($error[ 'max_leerlingen' ])) ? 'is-invalid' : ''; ?>"
                               id="max_leerlingen"
                               name="max_leerlingen"
                               required="required"
                               placeholder="Maximaal aantal leerlingen"
                               value="<?= (isset($_POST[ 'max_leerlingen' ])) ? $_POST[ 'max_leerlingen' ] : $row[ 'max_leerlingen' ]; ?>"
                        />
                        <?php if( isset($error[ 'max_leerlingen' ]) ) { ?>
                            <!-- max_leerlingen helper -->
                            <div class="invalid-feedback">
                                <?= $error[ 'max_leerlingen' ]; ?>
                            </div>
                        <?php } ?>
                    </div>

                </div>
                <div class="form-group">
                    <label for="whitelist">Privaat of Publiekelijk evenement? *</label>
                    <select class="form-control" id="whitelist" name="whitelist" required="required">
                        <option value="">Selecteer soort evenement</option>
                        <option
                            <?php if( isset($_POST[ 'whitelist' ]) ) { ?>
                                <?= ($_POST[ 'whitelist' ] === "1") ? 'selected' : ''; ?>
                            <?php } elseif( isset($row[ 'publiek' ]) ) { ?>
                                <?= ($row[ 'publiek' ] === "1") ? 'selected' : ''; ?>
                            <?php } ?>
                                value="1">Publiek
                        </option>

                        <option
                            <?php if( isset($_POST[ 'whitelist' ]) ) { ?>
                                <?= ($_POST[ 'whitelist' ] === "0") ? 'selected' : ''; ?>
                            <?php } elseif( isset($row[ 'publiek' ]) ) { ?>
                                <?= ($row[ 'publiek' ] === "0") ? 'selected' : ''; ?>
                            <?php } ?> value="0">Privaat
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="soort">Seleceer uw type evenement *</label>
                    <select class="form-control" id="soort" name="soort" required="required">
                        <option value="">Nog niet geselecteerd</option>
                        <?php foreach ($soorten as $key => $soort) { ?>
                            <option
                                    value="<?= $soort[ 'soort_id' ]; ?>"
                                <?php

                                if( isset($_POST[ 'soort' ]) )
                                {
                                    echo ($_POST[ 'soort' ] == $soort[ 'soort_id' ]) ? 'selected' : '';
                                }
                                else
                                {
                                    echo ($row[ 'soort_id' ] == $soort[ 'soort_id' ]) ? 'selected' : '';
                                }
                                ?>
                            >
                                <?= $soort[ 'soort' ]; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>


                <p>
                    <strong>Niet verplichte velden</strong>
                </p>


                <!-- vervoer form -->
                <div class="form-group">
                    <label for="vervoer">Vervoers middel</label>
                    <input type="text"
                           class="form-control <?= (isset($error[ 'vervoer' ])) ? 'is-invalid' : ''; ?>"
                           id="vervoer"
                           name="vervoer"
                           placeholder="Vervoers middel"
                           value="<?= (isset($_POST[ 'vervoer' ])) ? $_POST[ 'vervoer' ] : $row[ 'vervoer' ]; ?>"
                    />
                    <?php if( isset($error[ 'vervoer' ]) ) { ?>
                        <!-- vervoer helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'vervoer' ]; ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- locatie form -->
                <div class="form-group">
                    <label for="locatie">Locatie </label>
                    <input type="text"
                           class="form-control <?= (isset($error[ 'locatie' ])) ? 'is-invalid' : ''; ?>"
                           id="locatie"
                           name="locatie"
                           placeholder="locatie"
                           value="<?= (isset($_POST[ 'locatie' ])) ? $_POST[ 'locatie' ] : $row[ 'locatie' ]; ?>"
                    />
                    <?php if( isset($error[ 'locatie' ]) ) { ?>
                        <!-- locatie helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'locatie' ]; ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- lokaalnummer form -->
                <div class="form-group">
                    <label for="lokaalnummer">Lokaalnummer ( indien van toepassing ) </label>
                    <input type="text"
                           class="form-control <?= (isset($error[ 'lokaalnummer' ])) ? 'is-invalid' : ''; ?>"
                           id="lokaalnummer"
                           name="lokaalnummer"
                           placeholder="Lokaalnummer ( indien van toepassing )"
                           value="<?= (isset($_POST[ 'lokaalnummer' ])) ? $_POST[ 'lokaalnummer' ] : $row[ 'lokaalnummer' ]; ?>"
                    />
                    <?php if( isset($error[ 'lokaalnummer' ]) ) { ?>
                        <!-- lokaalnummer helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'lokaalnummer' ]; ?>
                        </div>
                    <?php } ?>
                </div>


                <!-- contractnr form -->
                <div class="form-group">
                    <label for="contractnr">Telefoonisch contact nummer</label>
                    <input type="text"
                           class="form-control <?= (isset($error[ 'contractnr' ])) ? 'is-invalid' : ''; ?>"
                           id="contractnr"
                           name="contractnr"
                           placeholder="Telefoonisch contact nummer"
                           value="<?= (isset($_POST[ 'contractnr' ])) ? $_POST[ 'contractnr' ] : $row[ 'contactnr' ]; ?>"
                    />
                    <?php if( isset($error[ 'contractnr' ]) ) { ?>
                        <!-- contractnr helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'contractnr' ]; ?>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-sm btn-primary">Wijzigen</button>
            </div>
        </div>

    </div>
</form>

