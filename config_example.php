<?php
// created by AUTHORNAME 26-10-2017

// TODO: FIX WITH $_SERVER variable;
if(!defined('Projectroot')) {
    define('Projectroot','/kbs');
}

mb_internal_encoding("UTF-8");

include_once 'database/database.php';
include_once 'generic_functions.php';
include_once 'authentication_functions.php';
include_once 'permission_functions.php';
