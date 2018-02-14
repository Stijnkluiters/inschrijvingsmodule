<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/6/2017
 * Time: 10:01
 */

//create database
$db = db();

//create a variable to catch errors
$error = [];

//filter and check the content in the post variable
/**soortnaam*/
if( isset($_POST[ 'submit' ]) )
{
    if( !isset($_POST[ 'soortnaam' ]) || empty($_POST[ 'soortnaam' ]) )
    {
        $error[ 'soortnaam' ] = ' soortnaam is verplicht.';
    }
    $soortnaam = filter_input(INPUT_POST, 'soortnaam', FILTER_SANITIZE_STRING);
    if( $soortnaam === false )
    {
        $error[ 'soortnaam' ] = ' het filteren van de soortnaam ging verkeerd';

    }

    /** benodigdheden */
    $benodigdheden = filter_input(INPUT_POST, 'benodigdheden', FILTER_SANITIZE_STRING);
    if( $benodigdheden === false )
    {
        $error[ 'benodigdheden' ] = 'het filteren van de benodigheden ging verkeerd';
    }
    //if there are no errors, continue to the query
    if( count($error) === 0 )
    {
        $stmt = $db->prepare("
       INSERT INTO soort (
       soort, benodigdheid
       )VALUES (
            ?,
            ?
        )");
        //connect the variables to the information in the query
        $stmt->execute(array(
            $soortnaam,
            $benodigdheden
        ));
        $successmessage = "soort is toegevoegd";
        redirect('/index.php?soorten=overzicht', $successmessage);

    }

}
?>
<!-- form where user can insert a 'soort' -->
<div class="card">
    <div class="card-header">
        <h4>Soort toevoegen
            <div class='pull-right'>
                <a href="<?= route('/index.php?soorten=overzicht') ?>" class=" btn btn-primary">Terug naar
                    soortenoverzicht</a>
            </div>
        </h4>
    </div>
    <form name="evenementWijzigen" method="post"
          action="<?= filter_var($_SERVER[ 'REQUEST_URI' ], FILTER_SANITIZE_STRING); ?>">
        <div class="col-sm-12">
            <div class="card-body">

                <!-- soortnaam form -->
                <div class="form-group">
                    <label for="soortnaam">Soortnaam *</label>
                    <input type="text"
                           class="form-control <?= (isset($error[ 'soortnaam' ])) ? 'is-invalid' : ''; ?>"
                           id="soortnaam"
                           name="soortnaam"
                           required="required"
                           placeholder="Soortnaam"
                           value="<?= (isset($_POST[ 'soortnaam' ])) ? $_POST[ 'soortnaam' ] : ''; ?>"
                    />
                    <?php if( isset($error[ 'soortnaam' ]) ) { ?>
                        <!-- soortnaam helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'soortnaam' ]; ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- benodigdheden textarea form -->
                <div class="form-group">
                    <label for="benodigdheden">Benodigdheden</label>
                    <textarea class="form-control <?= (isset($error[ 'benodigdheden' ])) ? 'is-invalid' : ''; ?>"
                              id="benodigdheden"
                              name="benodigdheden"
                              placeholder="Benodigdheden"
                              rows="3"
                    ><?= (isset($_POST[ 'benodigdheden' ])) ? $_POST[ 'benodigdheden' ] : ''; ?></textarea>

                    <?php if( isset($error[ 'benodigdheden' ]) ) { ?>
                        <!-- benodigdheden textarea helper -->
                        <div class="invalid-feedback">
                            <?= $error[ 'benodigdheden' ]; ?>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit" name="submit" class="btn btn-sm btn-primary">Toevoegen
            </div>
        </div>
    </form>
</div>