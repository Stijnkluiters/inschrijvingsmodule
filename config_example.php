<?php
// created by AUTHORNAME 26-10-2017


$db_name = '';
$db_user = '';
$db_pass = '';


// TODO: FIX WITH $_SERVER variable;
if(!defined('Projectroot')) {
    define('Projectroot','/kbs');
}


if(!defined('db_name')) {
    define('db_name',$db_name);
}
if(!defined('db_user')) {
    define('db_user',$db_name);
}
if(!defined('db_pass')) {
    define('db_pass',$db_name);
}

mb_internal_encoding("UTF-8");

include_once 'database/database.php';
include_once 'generic_functions.php';
include_once 'authentication_functions.php';
include_once 'permission_functions.php';
