<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 9-11-2017
 * Time: 20:50
 */

if(isset($_POST['invoeren'])) {
    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
//controleert of de correcte mime type wel geupload is in het formulier
    if(!in_array($_FILES['csv']['type'],$mimes)) {
        $error = 'Het bestandstype moet verplicht: .csv zijn.';
    } elseif($_FILES['csv']['error'] !== UPLOAD_ERR_OK) {
        // contoleren of het uploaden goed ging.
        $error = sprintf('er is iets fout gegaan tijdens het uploaden: %s' . $_FILES['csv']['error']);
    } else {
        // importeren van docenten proces is begonnen.
        $row = 1;
        if (($handle = fopen($_FILES['csv'], "r")) !== FALSE) {
            while (($data = fgetcsv($handle, filesize($_FILES['csv']['tmp_name']), ",")) !== FALSE) {
                // every row

                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {


                    // every column
                }
            }
            fclose($handle);
        }

    }
    if(isset($error)) {
        return $error;
    }

}
?>
<div class="card">
    <div class="card-header">
        <strong>docenten importeren </strong>
    </div>
    <div class="card-body">
        <form action="<?= route('/index.php?gebruiker=invoerendocent'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="file-input">File input</label>
                <div class="col-md-9">
                    <input type="file" id="file-input" name="file-input">
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
    </div>
</div>