<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 9-11-2017
 * Time: 20:50
 */

if( isset($_POST[ 'invoeren' ]) )
{
    if( !is_csv($_FILES[ 'csv' ][ 'type' ]) )
    {
        $error = 'Het bestandstype moet verplicht: .csv zijn.';
    }
    elseif( $_FILES[ 'csv' ][ 'error' ] !== UPLOAD_ERR_OK )
    {

        $error = sprintf('er is iets fout gegaan tijdens het uploaden: %s' . $_FILES[ 'csv' ][ 'error' ]);

    }
    else
    {
        $leerlingen = read_csv($_FILES[ 'csv' ]);
        //dump($leerlingen);
        //exit;
        if( empty($leerlingen) )
        {
            $error = 'Het csv bestand moet niet leeg zijn';
        }
        else
        {
            /**
             *   [0]=>
             * array(15) {
             * ["﻿Nummer"]=>
             * string(6) "488131"
             * ["Geslacht"]=>
             * string(3) "Man"
             * ["Roepnaam"]=>
             * string(6) "Casper"
             * ["Voorvoegsel"]=>
             * string(3) "van"
             * ["Achternaam"]=>
             * string(4) "Aken"
             * ["Opleiding"]=>
             * string(70) "95311BOL02 - Applicatie- en mediaontwikkeling (Applicatieontwikkelaar)"
             * ["Geboortedatum"]=>
             * string(10) "23-06-1998"
             * ["(W)Postcode en plaats"]=>
             * string(16) "3831 TD  LEUSDEN"
             * ["Begindatum"]=>
             * string(10) "01-08-2014"
             * ["Einddatum"]=>
             * string(10) "31-07-2015"
             * ["Plaatsing"]=>
             * string(3) "Nee"
             * ["LWOO"]=>
             * string(3) "Nee"
             * ["LGF"]=>
             * string(3) "Nee"
             * ["Groepscode"]=>
             * string(10) "NAXICA4A4A"
             * [""]=>
             * string(0) ""
             * }
             */
            // Hier gaan we controleren of de verplichte waardes ( gesteld door Jeroen ) wel zijn ingevuld.
            foreach ($leerlingen as $regelnummer => $leerling)
            {
                foreach ($leerling as $key => $item)
                {
                    // er zitten random bytes in het .csv bestand, die halen we met deze functie weg.
                    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $key); // Removes special chars.
                    $leerling[ $string ] = $leerling[ $key ];
                }
                if( !array_key_exists('Nummer', $leerling) )
                {

                    $error = ' Kolomnaam (leerling)Nummer is verplicht';
                }
                if( !array_key_exists('Geslacht', $leerling) )
                {
                    $error = ' Kolomnaam Geslacht is verplicht';
                }
                if( !array_key_exists('Roepnaam', $leerling) )
                {
                    $error = ' Kolomnaam Roepnaam is verplicht';
                }
                if( !array_key_exists('Voorvoegsel', $leerling) )
                {
                    $error = ' Kolomnaam Voorvoegsel is verplicht';
                }
                if( !array_key_exists('Achternaam', $leerling) )
                {
                    $error = ' Kolomnaam Achternaam is verplicht';
                }
                if( !array_key_exists('Opleiding', $leerling) )
                {
                    $error = ' Kolomnaam Opleiding is verplicht';
                }

                if( !array_key_exists('Geboortedatum', $leerling) )
                {
                    $error = ' Kolomnaam Geboortedatum is verplicht';
                }
                if( !array_key_exists('Begindatum', $leerling) )
                {
                    $error = ' Kolomnaam Begindatum is verplicht';
                }
                if( !array_key_exists('Einddatum', $leerling) )
                {
                    $error = ' Kolomnaam Einddatum is verplicht';
                }

                if( !array_key_exists('Groepscode', $leerling) )
                {
                    $error = ' Kolomnaam Groepscode is verplicht';
                }
                if(!empty($leerling[ '(W)Postcode en plaats' ])) {
                $weirddata = explode('  ', $leerling[ '(W)Postcode en plaats' ]);
                $postcode = trim($weirddata[ 0 ]);
                $plaats = trim($weirddata[ 1 ]);
                } else {
                    $postcode = '';
                    $plaats = '';
                }


                if( isset($error) )
                {
                    // breaks out of the foreach loop so the correct error line will be given.
                    break;
                }
                $db = db();

                /**
                 * Controleer of leerlingnummer al bestaad, dit is een unieke waarde.
                 */
                $stmt = $db->prepare('select leerlingnummer from leerling where leerlingnummer = :leerlingnummer');
                $stmt->bindParam('leerlingnummer', $leerling[ 'Nummer' ]);
                $stmt->execute();
                $rowcount = $stmt->rowCount();
                // check if the medewerker is deleted; if so, harddelete afterall

                if( $rowcount > 0 )
                {
                    $stmt = $db->prepare('update leerling SET 
                            geslacht = ?,
                            roepnaam = ?,
                            tussenvoegsel = ?,
                            achternaam = ?,
                            opleiding = ?,
                            geboortedatum = ?,
                            postcode = ?,
                            plaats = ?,
                            begindatum = ?,
                            einddatum = ?,
                            plaatsing = ?,
                            LWOO = ?,
                            LGF = ?,
                            groepscode = ?,
                            deleted = ?            
                            WHERE leerlingnummer = ?
                          ');
                    $stmt->execute(array(
                            $leerling[ 'Geslacht' ],
                            $leerling[ 'Roepnaam' ],
                            $leerling[ 'Voorvoegsel' ],
                            $leerling[ 'Achternaam' ],
                            $leerling[ 'Opleiding' ],
                            date('Y-m-d', strtotime($leerling[ 'Geboortedatum' ])),
                            $postcode,
                            $plaats,
                            date('Y-m-d', strtotime($leerling[ 'Begindatum' ])),
                            date('Y-m-d', strtotime($leerling[ 'Einddatum' ])),
                            $leerling[ 'Plaatsing' ],
                            $leerling[ 'LWOO' ],
                            $leerling[ 'LGF' ],
                            $leerling[ 'Groepscode' ],
                            0,
                            $leerling[ 'Nummer' ]
                        )
                    );
                }
                else
                {
                    /** IMPORTING NEW FRESH GENERATED ACCOUNTS. **/

                    $account_id = generateRandomAccountForRole($leerling[ 'Nummer' ], 'leerling');

                    $stmt = $db->prepare('INSERT INTO leerling 
                            (
                            geslacht,
                            leerlingnummer, 
                            account_id, 
                            roepnaam, 
                            tussenvoegsel, 
                            achternaam, 
                            opleiding,
                            geboortedatum,
                            postcode,
                            plaats,
                            begindatum,
                            einddatum,
                            plaatsing,
                            LWOO,
                            LGF,
                            groepscode
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
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?
                            )');
                    $stmt->execute(array(

                        $leerling[ 'Geslacht' ],
                        $leerling[ 'Nummer' ],
                        $account_id,
                        $leerling[ 'Roepnaam' ],
                        $leerling[ 'Voorvoegsel' ],
                        $leerling[ 'Achternaam' ],
                        $leerling[ 'Opleiding' ],
                        (!empty($leerling[ 'Geboortedatum' ]) ? date('Y-m-d', strtotime($leerling[ 'Geboortedatum' ])) : null),
                        $postcode,
                        $plaats,
                        date('Y-m-d', strtotime($leerling[ 'Begindatum' ])),
                        date('Y-m-d', strtotime($leerling[ 'Einddatum' ])),
                        $leerling[ 'Plaatsing' ],
                        $leerling[ 'LWOO' ],
                        $leerling[ 'LGF' ],
                        $leerling[ 'Groepscode' ]
                    ));
                }

            }
        }
        redirect('/index.php?gebruiker=overzichtleerling');
    }
}
?>

<div class="card">
    <form action="<?= route('/index.php?gebruiker=invoerenleerling'); ?>" method="post" enctype="multipart/form-data"
          class="form-horizontal">

        <div class="card-header">
            <strong>Leerlingen Importeren</strong>
        </div>
        <?php

        if( isset($error) )
        {
            error($error);
        }
        ?>
        <div class="card-body">

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="docenten-input">Leerlingen CSV bestand</label>
                <div class="col-md-9">
                    <input required="required" type="file" id="docenten-input" name="csv">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary" name="invoeren"><i class="fa fa-dot-circle-o"></i>
                Importeren
            </button>
        </div>
    </form>
</div>
