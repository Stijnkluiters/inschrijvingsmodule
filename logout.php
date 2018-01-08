<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 17-11-2017
 * Time: 11:27
 */
//include 'authentication_functions.php';
session_start();
session_regenerate_id(true);
unset($_SESSION['account']);
$_SESSION['message'] = 'Je bent uigelogd!';
if (headers_sent()) {
    echo '<script> location.replace("login.php"); </script>';
} else {
    header(sprintf('Location: %s', 'login.php'));
}