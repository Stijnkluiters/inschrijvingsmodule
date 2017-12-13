<?php
// created by AUTHORNAME 26-10-2017
$db_name = '';
$db_user = '';
$db_pass = '';


/**
 * https://github.com/PHPMailer/PHPMailer
 */
$mailhost = 'localhost';
$mailSMTP = false;
$mailSMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
$mailPort = 25;
$mailuser = 'example';
$mailpassword = 'example';
$mailFromEmail = 'example@hotmail.com';
$mailFromUser = 'example';


if(!defined('mailhost')) {
    define('mailhost',$mailhost);
}
if(!defined('mailSMTP')) {
    define('mailSMTP',$mailSMTP);
}
if(!defined('mailSMTPSecure')) {
    define('mailSMTPSecure',$mailSMTPSecure);
}
if(!defined('mailPort')) {
    define('mailPort',$mailPort);
}
if(!defined('mailuser')) {
    define('mailuser',$mailuser);
}
if(!defined('mailpassword')) {
    define('mailpassword',$mailpassword);
}

if(!defined('mailFromEmail')) {
    define('mailFromEmail',$mailFromEmail);
}
if(!defined('mailFromUser')) {
    define('mailFromUser',$mailFromUser);
}



// TODO: FIX WITH $_SERVER variable;
if(!defined('Projectroot')) {
    define('Projectroot','/kbs');
}


if(!defined('db_name')) {
    define('db_name',$db_name);
}
if(!defined('db_user')) {
    define('db_user',$db_user);
}
if(!defined('db_pass')) {
    define('db_pass',$db_pass);
}

mb_internal_encoding("UTF-8");

include_once 'database/database.php';
include_once 'generic_functions.php';
include_once 'authentication_functions.php';
include_once 'user_functions.php';
include_once 'permission_functions.php';
include_once 'file_functions.php';
include_once 'request_functions.php';

require_once 'vendor/PHPMailerException.php';
require_once 'vendor/PHPMailer.php';
require_once 'vendor/SMTP.php';



startsession();

