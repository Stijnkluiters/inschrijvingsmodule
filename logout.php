<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 17-11-2017
 * Time: 11:27
 */
// logt de ingelogde gebruiker uit.
include 'config.php';
logout();
redirect('/index.php','U bent uitgelogd!');