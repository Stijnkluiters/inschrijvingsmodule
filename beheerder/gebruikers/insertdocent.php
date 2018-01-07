<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 9-11-2017
 * Time: 20:50
 */

if( isset($_POST[ 'invoeren' ]) )
{

    if( !is_csv($_FILES[ 'csv' ]) )
    {
        $error = 'Het bestandstype moet verplicht: .csv zijn.';
    }
    elseif( $_FILES[ 'csv' ][ 'error' ] !== UPLOAD_ERR_OK )
    {
        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );


        $error = sprintf('er is iets fout gegaan tijdens het uploaden: %s' , $phpFileUploadErrors[$_FILES['csv']['error']]);

    } elseif($_FILES['csv']['size'] > 10000000) {

        $error = 'Maximaal bestandgrote is 10MB';

    }
    else
    {
        /**
         * [0]=>
         * array(9) {
         * ["Roepnaam"]=>
         * string(5) "Danny"
         * ["Voorvoegsel"]=>
         * string(6) "van de"
         * ["Achternaam"]=>
         * string(4) "Beek"
         * ["Afkorting"]=>
         * string(7) "dabe852"
         * ["Functie"]=>
         * string(0) ""
         * ["Geslacht"]=>
         * string(3) "Man"
         * ["Geboortedatum"]=>
         * string(9) "14-7-1975"
         * ["Gekoppelde locaties"]=>
         * string(0) ""
         * ["Telefoon 1"]=>
         * string(11) "31307548177"
         * }
         */
        $medewerkers = read_csv($_FILES[ 'csv' ]);
        if( empty($medewerkers) )
        {
            $error = 'Het csv bestand moet niet leeg zijn';
        }
        else
        {
            $i = 0;
            // Hier gaan we controleren of de verplichte waardes ( gesteld door Jeroen ) wel zijn ingevuld.
            foreach ($medewerkers as $regelnummer => $medewerker)
            {
                /**
                 * Afkorting
                 * Roepnaam,
                 * Achternaam,
                 * Geslacht,
                 * Geboortedatum,
                 * Telefoon 1,
                 */
                if( !array_key_exists('Afkorting', $medewerker) )
                {
                    $error = ' Kolomnaam afkorting is verplicht';
                }
                if( !array_key_exists('Roepnaam', $medewerker) )
                {
                    $error = ' Kolomnaam Roepnaam is verplicht';
                }
                if( !array_key_exists('Achternaam', $medewerker) )
                {
                    $error = ' Kolomnaam Achternaam is verplicht';
                }
                if( !array_key_exists('Geslacht', $medewerker) )
                {
                    $error = ' Kolomnaam Geslacht is verplicht';
                }
                if( !array_key_exists('Geboortedatum', $medewerker) )
                {
                    $error = ' Kolomnaam Geboortedatum is verplicht';
                }
                if( !array_key_exists('Telefoon 1', $medewerker) )
                {
                    $error = ' Kolomnaam Telefoon 1 is verplicht';
                }


                if( isset($error) )
                {
                    // breaks out of the foreach loop so the correct error line will be given.
                    break;
                }
                $db = db();

                /**
                 * Controleer of afkorting al bestaad, unieke waarde.
                 */
                $stmt = $db->prepare('select afkorting from medewerker where afkorting = :afkorting');
                $stmt->bindParam('afkorting', $medewerker[ 'Afkorting' ]);
                $stmt->execute();
                $rowcount = $stmt->rowCount();
                // check if the medewerker is deleted; if so, harddelete afterall

                if( $rowcount )
                {


                    $stmt = $db->prepare('update medewerker set 
                            roepnaam = ?, 
                            tussenvoegsel = ?, 
                            achternaam = ?, 
                            functie = ?, 
                            geslacht = ?, 
                            geboortedatum = ?, 
                            locatie = ?, 
                            telefoon = ?,
                            deleted = ?                          
                            WHERE afkorting = ?
                          ');
                    $stmt->execute(array(
                            $medewerker[ 'Roepnaam' ],
                            $medewerker[ 'Voorvoegsel' ],
                            $medewerker[ 'Achternaam' ],
                            $medewerker[ 'Functie' ],
                            $medewerker[ 'Geslacht' ],
                            date('Y-m-d', strtotime($medewerker[ 'Geboortedatum' ])),
                            $medewerker[ 'Gekoppelde locaties' ],
                            $medewerker[ 'Telefoon 1' ],
                            0,
                            $medewerker[ 'Afkorting' ]
                        )
                    );

                }
                else
                {
                    /** IMPORTING NEW FRESH GENERATED ACCOUNTS. **/
                    $account_id = generateRandomAccountForRole($medewerker[ 'Afkorting' ], 'docent');

                    $stmt = $db->prepare('INSERT INTO medewerker 
                            (
                            afkorting, 
                            account_id, 
                            roepnaam, 
                            tussenvoegsel, 
                            achternaam, 
                            functie, 
                            geslacht, 
                            geboortedatum, 
                            locatie, 
                            telefoon
                            ) 
                            VALUES 
                            (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?
                            )');
                    $stmt->execute(array(
                        $medewerker[ 'Afkorting' ],
                        $account_id,
                        $medewerker[ 'Roepnaam' ],
                        $medewerker[ 'Voorvoegsel' ],
                        $medewerker[ 'Achternaam' ],
                        $medewerker[ 'Functie' ],
                        $medewerker[ 'Geslacht' ],
                        date('Y-m-d', strtotime($medewerker[ 'Geboortedatum' ])),
                        $medewerker[ 'Gekoppelde locaties' ],
                        $medewerker[ 'Telefoon 1' ]
                    ));
                }

                $i++;
            }
        }
        redirect('/index.php?gebruiker=overzichtmedewerker',$i.' medewerkers geimporteerd');
    }
}
?>

<div class="card">
    <form action="<?= route('/index.php?gebruiker=invoerenmedewerker'); ?>" method="post" enctype="multipart/form-data"
          class="form-horizontal">

        <div class="card-header">
            <strong>Medewerkers Importeren</strong>
        </div>
        <?php

        if( isset($error) )
        {
            error($error);
        }
        ?>
        <div class="card-body">

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="docenten-input">Medewerkers CSV bestand</label>
                <div class="col-md-9">
                    <input required="required" type="file" id="docenten-input" name="csv">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary" name="invoeren"><i class="fa fa-dot-circle-o"></i>
                Submit
            </button>
        </div>
    </form>
</div>
