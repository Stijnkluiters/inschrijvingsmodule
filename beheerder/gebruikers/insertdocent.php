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


        $medewerkers = read_csv($_FILES['csv']);


        // Hier gaan we controleren of de verplichte waardes ( gesteld door Jeroen ) wel zijn ingevuld.
        foreach ($medewerkers as $regelnummer => $medewerker) {


            // KEY + 1 zodat het sleutel van de array + 1 is zodat de correcte regellijn wordt weergeven.


            /**
             * Afkorting
             */
            if (strlen($medewerker['Afkorting']) === 0) {
                $error = 'Afkorting is verplicht op regel: ' . ($regelnummer + 1);
            }
            /**
             * Roepnaam
             */
            if (strlen($medewerker['Roepnaam']) === 0) {
                $error = 'roepnaam is verplicht op regel: ' . ($regelnummer + 1);
            }
            /**
             * Achternaam
             */
            if (strlen($medewerker['Achternaam']) === 0) {
                $error = 'Achternaam is verplicht op regel: ' . ($regelnummer + 1);
            }
            /**
             * Geslacht
             */
            if (strlen($medewerker['geslacht']) === 0) {
                $error = 'Geslacht is verplicht op regel: ' . ($regelnummer + 1);
            }
            /**
             * Geboortedatum
             */
            if (strlen($medewerker['Geboortedatum']) === 0) {
                $error = 'Geboortedatum is verplicht op regel: ' . ($regelnummer + 1);
            }
            // check if given date can be converted to strtotime, if not. its false which means incorrect date.
            if (!strtotime($_POST['Geboortedatum'])) {
                $error = ' Geboortedatum moet een datum zijn.';
            }


            // Geboortedatum; controleert of het daadwerkelijk een datum is.
            if (strtotime($medewerker['Geboortedatum']) === false) {
                $error = 'Geboortedatum moet een datum zijn op regel: ' . ($regelnummer + 1);
            }
            //if(strlen($medewerker['Telefoon 1']) === 0) {
            //    $error = 'Telefoonnummer is verplicht. op regel: ' . ($regelnummer + 1);
            //}


            if (isset($error)) {
                // breaks out of the foreach loop so the correct error line will be given.
                break;
            }


            // all checks have been done. add them to the database.

            $db = db();
            //$db->beginTransaction();
            try {
                $stmt = $db->prepare('select afkorting from gebruiker where afkorting = :afkorting');
                $stmt->bindParam('afkorting', $medewerker['Afkorting']);
                $stmt->execute();
                $rowcount = $stmt->rowCount();
                if ($rowcount === 0) {
                    $stmt = $db->prepare('INSERT INTO gebruiker (roepnaam,voorvoegsel,achternaam,afkorting,geslacht,geboortedatum) VALUES 
                    (?,?,?,?,?,?)');
                    $stmt->execute(array(
                        $medewerker['Roepnaam'],
                        $medewerker['Voorvoegsel'],
                        $medewerker['Achternaam'],
                        $medewerker['Afkorting'],
                        $medewerker['Geslacht'],
                        date('Y-m-d', strtotime($medewerker['Geboortedatum'])),
                    ));
                    $medewerker_id = $db->lastInsertId();

                    check_if_role_exists('docent');


                }
                //
            } catch (PDOException $exception) {
                throw new PDOException($exception->getMessage());
            }
        }
        redirect('/index.php?gebruiker=overzichtdocent');
    }
}
?>

<div class="card">
    <form action="<?= route('/index.php?gebruiker=invoerendocent'); ?>" method="post" enctype="multipart/form-data"
          class="form-horizontal">

        <div class="card-header">
            <strong>Docenten Importeren</strong>
        </div>
        <?php

        if (isset($error)) {
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
