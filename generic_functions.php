<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 22:14
 *
 * @param $url
 */


function redirect($url)
{
    if( !is_string($url) )
    {
        throw new Exception($url . ' is not an String you silly');
    }

    $url = filter_url($url);

    return header(sprintf('Location: %s',  $url));
}

function filter_url($url)
{

    /*
     * How does it work?
     *
     *   In PHP you can get particular character of a string with array index notation.
     *  $url[0] is the first character of a string (if $url is a string).
     *
     */
    if( $url[ 0 ] !== '/' )
    {
        $url .= '/';
    }

    return $url;
}
// return correct url for <ahref tags or stylesheet links
function route($url)
{
    return Projectroot.$url;
}

function startsession()
{

    if( session_status() == PHP_SESSION_NONE )
    {
        session_start();
    }
}