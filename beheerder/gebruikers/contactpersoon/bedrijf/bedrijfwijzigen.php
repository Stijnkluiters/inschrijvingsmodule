<?php

$db = db();
$contact_id = intval($_GET[ 'contact_id' ]);

// controlleer of het contact id wel een nummeriek getal is. anders doorverwijzen naar een andere pagina.
if( !filter_var($contact_id, FILTER_VALIDATE_INT) )
{
    redirect('/index.php?contactpersoon=overzicht', 'contactID moet een nummeriek getal zijn');
}


// controleren of het id wel in de database bestaat
$stmt = $db->prepare('select contact_id from contactpersoon where contact_id = ?');
$stmt->execute(array( $contact_id ));
if( $stmt->rowCount() === 0 )
{
    redirect('/index.php?contactpersoon=overzicht', 'contact persoon niet gevonden in de database');
}
$stmt = $db->prepare('select * 
    from brance b
    join contactpersoon c ON c.branche_id = b.branche_id  
    join inventarisatie i ON b.inventarisatie_id = i.inventarisatie_id
    join bedrijf be ON b.bedrijf_id = be.bedrijf_id
    where c.contact_id = ?
');
$stmt->execute(array( $contact_id ));
$companyInfo = $stmt->fetch();
// show branches.
$error = [];
if( isset($_POST[ 'inventarisatie_submit' ]) )
{

    /**
     * check all required fields
     */
    /**
     * branche
     */
    if( !isset($_POST[ 'branche' ]) )
    {
        $error[ 'branche' ] = 'branche is verplicht';
    }
    else
    {
        $branche = filter_input(INPUT_POST, 'branche', FILTER_SANITIZE_STRING);
    }
    /**
     * Webadres
     */
    $webadres = filter_input(INPUT_POST, 'webadres', FILTER_SANITIZE_STRING);

    /**
     * Adres
     */
    if( !isset($_POST[ 'adres' ]) )
    {
        $error[ 'adrse' ] = 'adres is verplicht';
    }
    else
    {
        $adres = filter_input(INPUT_POST, 'adres', FILTER_SANITIZE_STRING);
    }

    /**
     * Postcode
     */
    if( !isset($_POST[ 'postcode' ]) )
    {
        $error[ 'postcode' ] = 'Postcode is verplicht';
    }
    else
    {
        $postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
    }


    /**
     * Plaatsnaam
     */
    if( !isset($_POST[ 'plaatsnaam' ]) )
    {
        $error[ 'plaatsnaam' ] = 'plaatsnaam is verplicht';
    }
    else
    {
        $plaatsnaam = filter_input(INPUT_POST, 'plaatsnaam', FILTER_SANITIZE_STRING);
    }

    /**
     * contactnr
     */
    if( !isset($_POST[ 'contactnr' ]) )
    {
        $error[ 'contactnr' ] = 'contactnr is verplicht';
    }
    else
    {
        $contactnr = filter_input(INPUT_POST, 'contactnr', FILTER_SANITIZE_STRING);
    }


    if( count($error) === 0 )
    {


        $db->prepare('update inventarisatie SET
          vakbebied = ?,
          onderwerp = ?,
          aantal_gastcolleges = ?,
          voorkeur_dag = ?,
          voorkeur_dagdeel = ?,
          hulpmiddel = ?,
          doelstelling = ?,
          verwachting = ?,       
          WHERE inventarisatie_id = ?
        ');
        $r = $stmt->execute(array(
            $vakgebied,
            $onderwerp,
            $aantal_gastcolleges,
            $voorkeur_dag,
            $voorkeur_dagdeel,
            $hulpmiddel,
            $doelstelling,
            $verwachting,
            $inventarisatie_id
        ));

        if( $r )
        {
            redirect('index.php?gebruiker=overzichtcontactpersonen', 'Inventarisatie formulier geüpdate!');
        }
        else
        {
            $error[ 'vakgebied' ] = 'er ging iets mis met het Updaten van het inventarisatie formulier ';
        }
    }


}
if( isset($_POST[ 'branche_submit' ]) )
{
    /**
     * check all required fields
     */
    /**
     * branche
     */
    if( !isset($_POST[ 'branche' ]) )
    {
        $error[ 'branche' ] = 'branche is verplicht';
    }
    else
    {
        $branche = filter_input(INPUT_POST, 'branche', FILTER_SANITIZE_STRING);
    }
    /**
     * Webadres
     */
    $webadres = filter_input(INPUT_POST, 'webadres', FILTER_SANITIZE_STRING);

    /**
     * Adres
     */
    if( !isset($_POST[ 'adres' ]) )
    {
        $error[ 'adrse' ] = 'adres is verplicht';
    }
    else
    {
        $adres = filter_input(INPUT_POST, 'adres', FILTER_SANITIZE_STRING);
    }

    /**
     * Postcode
     */
    if( !isset($_POST[ 'postcode' ]) )
    {
        $error[ 'postcode' ] = 'Postcode is verplicht';
    }
    else
    {
        $postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
    }


    /**
     * Plaatsnaam
     */
    if( !isset($_POST[ 'plaatsnaam' ]) )
    {
        $error[ 'plaatsnaam' ] = 'plaatsnaam is verplicht';
    }
    else
    {
        $plaatsnaam = filter_input(INPUT_POST, 'plaatsnaam', FILTER_SANITIZE_STRING);
    }

    /**
     * contactnr
     */
    if( !isset($_POST[ 'contactnr' ]) )
    {
        $error[ 'contactnr' ] = 'contactnr is verplicht';
    }
    else
    {
        $contactnr = filter_input(INPUT_POST, 'contactnr', FILTER_SANITIZE_STRING);
    }


    if( count($error) === 0 )
    {


        $db->prepare('update branche SET
            branche = ?,
            webadres = ?,
            adres = ?,
            postcode = ?,
            plaatsnaam = ?,
            contactnr = ?,
            deleted = ?,
            where branche_id = ?
        ');
        $r = $stmt->execute(array(
            $branche,
            $webadres,
            $adres,
            $postcode,
            $plaatsnaam,
            $contactnr,
            0,
            $branche_id
        ));

        if( $r )
        {
            redirect('index.php?gebruiker=overzichtcontactpersonen', 'Branche geüpdate!');
        }
        else
        {
            $error[ 'branche' ] = 'er ging iets mis met het opslaan van de branche';
        }
    }


}


if( empty($companyInfo) )
{
    print '<h2>er is niet genoeg bedrijfsinformatie opgeslagen?</h2>';

}
else
{
    ?>
    <div class="row">

        <h6>Uw bedrijfsnaam
            <small><?= $companyInfo[ 'bedrijfsnaam' ]; ?>
                <code>.btn-sm</code>
            </small>
        </h6>


        <div class="col-sm-6">
            <form action="<?= $_SERVER[ 'PHP_SELF' ]; ?>" method="post">
                <input type="hidden" name="inventarisatie_id" value="<?= $companyInfo[ 'i.inventarisatie_id' ]; ?>">
                <div class="card">
                    <div class="card-header">
                        <strong>Inventarisatie formulier</strong>
                        <small>wijzigen</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="vakgebied">Welk vakgebied?</label>
                                    <textarea id="vakgebied"
                                              name="vakgebied"><?= $companyInfo[ 'vakgebied' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="onderwerp">Welk onderwerp gaat het over?</label>
                                    <textarea id="onderwerp"
                                              name="onderwerp"><?= $companyInfo[ 'onderwerp' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="aantal_gastcolleges">aantal gast colleges?</label>
                                    <textarea id="aantal_gastcolleges"
                                              name="aantal_gastcolleges"><?= $companyInfo[ 'aantal_gastcolleges' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="voorkeur_dag">Welke dag heeft het als voorkeur?</label>
                                    <textarea id="voorkeur_dag"
                                              name="voorkeur_dag"><?= $companyInfo[ 'voorkeur_dag' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="voorkeur_dagdeel">Welke voorkeur dagdeel?</label>
                                    <textarea id="voorkeur_dagdeel"
                                              name="voorkeur_dagdeel"><?= $companyInfo[ 'voorkeur_dagdeel' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="hulpmiddel">Welke hulpmiddelen?</label>
                                    <textarea id="hulpmiddel"
                                              name="hulpmiddel"><?= $companyInfo[ 'hulpmiddel' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="doelstelling">Wat is de doelstelling?</label>
                                    <textarea id="doelstelling"
                                              name="doelstelling"><?= $companyInfo[ 'doelstelling' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="verwachting">Wat is het verwachte resultaat?</label>
                                    <textarea id="verwachting"
                                              name="verwachting"><?= $companyInfo[ 'verwachting' ]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="inventarisatie_submit" class="btn btn-sm btn-primary"><i
                                    class="fa fa-dot-circle-o"></i> Inventarisatie wijzigen
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!--/.col-->

        <div class="col-sm-6">
            <form action="<?= $_SERVER[ 'PHP_SELF' ]; ?>" method="post">
                <input type="hidden" name="branche_id" value="<?= $companyInfo[ 'b.branche_id' ]; ?>">
                <div class="card">
                    <div class="card-header">
                        <strong>Branche info</strong>
                        <small>Wijzigen</small>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="branche">Uw Branche*</label>
                            <input type="text"
                                   required="required"
                                   class="form-control"
                                   id="branche"
                                   name="branche"
                                   placeholder="Uw branche naam"
                                   value="<?= $companyInfo[ 'branche' ]; ?>">
                        </div>
                        <div class="form-group">
                            <label for="website">Uw Website</label>
                            <input type="text"
                                   class="form-control"
                                   id="website"
                                   name="website"
                                   value="<?= $companyInfo[ 'webadres' ]; ?>"
                                   placeholder="Uw websiteadres">
                        </div>


                        <div class="row">

                            <div class="form-group col-sm-8">
                                <label for="adres">Uw adres</label>
                                <input type="text"
                                       class="form-control"
                                       id="adres"
                                       name="adres"
                                       value="<?= $companyInfo[ 'adres' ]; ?>"
                                       placeholder="Uw adres">
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="postcode">Uw postcode</label>
                                <input type="text"
                                       class="form-control"
                                       id="postcode"
                                       name="postcode"
                                       value="<?= $companyInfo[ 'postcode' ]; ?>"
                                       placeholder="Uw postcode">
                            </div>

                        </div>
                        <!--/.row-->
                        <div class="form-group">
                            <label for="plaatsnaam">Uw Plaatsnaam</label>
                            <input type="text"
                                   class="form-control"
                                   id="plaatsnaam"
                                   name="plaatsnaam"
                                   value="<?= $companyInfo[ 'plaatsnaam' ]; ?>"
                                   placeholder="Uw plaatsnaam">
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label for="contractnr">Uw branche telefoonnummer</label>
                                <input type="text"
                                       class="form-control"
                                       id="contactnr"
                                       name="contactnr"
                                       value="<?= $companyInfo[ 'contactnr' ]; ?>"
                                       placeholder="Uw contactnummer">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" name="branche_submit" class="btn btn-sm btn-primary"><i
                                    class="fa fa-dot-circle-o"></i> branche wijzigen
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!--/.col-->

    </div>
<?php } ?>







