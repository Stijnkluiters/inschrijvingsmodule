<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 21-11-2017
 * Time: 16:25
 */

function is_csv($filetype)
{

    // $filetype = $_FILES[ 'file' ][ 'type' ]
    $mimes = array( 'application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv' );
    if( !in_array($filetype, $mimes) )
    {
        return false;

    }

    return true;
}

function read_csv($file)
{

    // hier controleren we voor de laatste keer of het daadwerkelijk een CSV bestand is.
    if( !is_csv($file[ 'type' ]) )
    {
        throw new \Exception('Het bestandstype moet verplicht: .csv zijn.');
    }
    $firstrow = null;
    $data = array();
    // open het tijdelijke bestand, sinds we geen fysiek bestand op de server willen opslaan.
    if( ($handle = fopen($file[ 'tmp_name' ], 'r')) !== false )
    {
        // gebruik de handle om het csv bestand te openen
        while ( ($row = fgetcsv($handle, 1000, ';')) !== false )
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

