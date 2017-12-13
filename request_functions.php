<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 3-12-2017
 * Time: 23:14
 */


function post_request($url, $data = null)
{
    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if($data !== null) {

        $fields_string = http_build_query($data);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    }

    if($_SERVER['SERVER_NAME'] === 'localhost') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }


    // $output contains the output string
    $output = curl_exec($ch);

    if (FALSE === $output) {
        throw new Exception(curl_error($ch), curl_errno($ch));
    }

    // close curl resource to free up system resources
    curl_close($ch);
    return $output;
}