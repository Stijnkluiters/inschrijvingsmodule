<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 9-11-2017
 * Time: 20:50
 */

if(isset($_POST['invoeren'])) {
    $_FILES['csv'];
    $name =

    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if(!in_array($_FILES['file']['type'],$mimes)) {
        $error = 'Het bestandstype moet verplicht: .csv zijn.';
    } elseif($_FILES['csv']['error'] !== UPLOAD_ERR_OK) {
        $error = sprintf('er is iets fout gegaan tijdens het uploaden: %s' . $_FILES['csv']['error']);
    } else {

        $row = 1;
        if (($handle = fopen("test.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }

    }




}