<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 17-11-2017
 * Time: 11:27
 */
include 'authentication_functions.php';
logout();
redirect('/index.php','U bent uitgelogd!');