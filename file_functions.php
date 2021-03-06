<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 21-11-2017
 * Time: 16:25
 */
// controleren of het een csv bestand is
function is_csv($file)
{

    if(empty($file)) {
        throw new \Exception('No file provided...');
    }

    // $filetype = $_FILES[ 'file' ][ 'type' ]
    $allowedExtensions = array( 'csv' );
    /** @var SplFileInfo $file
     * gather fileinformation by a class.
     */

    $file = new SplFileInfo($file['name']);
    $fileExtension = $file->getExtension();
    return in_array($fileExtension, $allowedExtensions);
}
// Het uitlezen van een csv bestand
function read_csv($file)
{

    // hier controleren we voor de laatste keer of het daadwerkelijk een CSV bestand is.
    if( !is_csv($file) )
    {
        throw new \Exception('Het bestandstype moet verplicht: .csv zijn.');

    }

    // maximaal 10 MB (1000 * (1000 * 10'mb'))
    if($file['size'] > 10000000) {
        throw new \Exception('Bestandsgrote overschreven. maximaal 10MB');
    }

    $firstrow = null;
    $data = array();
    // open het tijdelijke bestand, sinds we geen fysiek bestand op de server willen opslaan.
    if( ($handle = fopen($file[ 'tmp_name' ], 'r')) !== false )
    {
        // gebruik de handle om het csv bestand te openen
        while ( ($row = fgetcsv($handle, filesize($file['tmp_name']), ';')) !== false )
        {
            // de eerste rij in een CSV bestand zijn de sleutels die overeen komen in de database.
            if( !$firstrow )
            {
                $firstrow = array_map('trim',$row);
            }
            // alles behalve de eerste rij zijn waardes in een CSV bestand dus die voegen we samen tot een array.
            else
            {
                $data[] = array_combine($firstrow, $row);
            }
        }
        fclose($handle);
    }

    return $data;
}

