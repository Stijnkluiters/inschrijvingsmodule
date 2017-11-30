<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 17:38
 *
 *
 *
 *
 */

// check if user is logged in.


//
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include_once 'config.php';
startsession();

if( !isset($_SESSION[ authenticationSessionName ]) )
{
    redirect('/login.php');
}
else
{

    $user = AuthUserDetails();

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ROC-midden Nederland evenementenmodule">
    <meta name="author" content="Stijn Kluiters, ...">
    <meta name="keyword"
          content="Bootstrap,Template,Open,Source,AngularJS,Angular,Angular2,Angular 2,Angular4,Angular 4,jQuery,CSS,HTML,RWD,Dashboard,React,React.js,Vue,Vue.js">
    <title>Inschrijfmodule</title>

    <!-- Icons -->
    <link href="public/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Main styles for this application -->
    <link href="public/css/style.css" rel="stylesheet">
    <?php

    if( isset($_GET[ 'evenementen' ]) )
    {
        echo '<link href="' . route('/public/css/evenementen.css') . '" rel="stylesheet"/>';
    }
    ?>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>

    <ul class="nav navbar-nav  ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="true" aria-expanded="false">
                <img src="public/img/user.png" class="img-fluid img-avatar" alt="<?= $user['email']; ?>">
                <span class="d-md-down-none"><?= $user['email']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header">
                    <i class="fa fa-user-circle"></i><strong class="ml-1"><?= formatusername($user); ?></strong>
                </div>
                <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profiel</a>
                <a class="dropdown-item" href="<?= route('/logout.php') ?>"><i class="fa fa-lock"></i> Logout</a>
            </div>
        </li>
    </ul>
</header>
<div class="app-body">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('/index.php') ?>"><i class="icon-speedometer"></i>
                        Dashboard </a>
                </li>
                <li class="nav-title">
                    Interacties
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('/index.php?evenementen=alles') ?>"><i
                                class="icon-pie-chart"></i> Evenementen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('/index.php?gebruiker=overzichtleerling'); ?>">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        Leerlingen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= route('/index.php?gebruiker=overzichtdocent'); ?>">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        Docenten
                    </a>
                </li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>

    <!-- Main content -->
    <main class="main">
        <!-- start content -->
        <div class="container-fluid">
            <?php


            if( count($_GET) === 0 )
            {
                //include 'dashboard.php';
            }
            else
            {
                // gebruikers

                if( isset($_GET[ 'gebruiker' ]) )
                {
                    if( $_GET[ 'gebruiker' ] === 'alles' )
                    {
                        require 'beheerder/gebruikers/all.php';
                        // alle gebruikers
                    }
                    elseif( $_GET[ 'gebruiker' ] == 'invoerendocent' )
                    {
                        require 'beheerder/gebruikers/insertdocent.php';
                    }
                    elseif( $_GET[ 'gebruiker' ] == 'overzichtleerling' )
                    {
                        require 'beheerder/gebruikers/overzicht_leerling.php';
                    }
                    elseif( $_GET[ 'gebruiker' ] == 'overzichtdocent' )
                    {
                        require 'beheerder/gebruikers/bekijken_docent.php';
                    }
                    elseif( $_GET[ 'gebruiker' ] == 'editleerling' )
                    {

                        require 'beheerder/gebruikers/edit_leerling.php';

                    }
                    else
                    {
                        exit;
                        // specifieke gebruiker
                    }
                }

                // evenementen
                if( isset($_GET[ 'evenementen' ]) )
                {
                    if( $_GET[ 'evenementen' ] === 'alles' )
                    {
                        include 'evenement/bekijken.php';
                    }
                    elseif( $_GET[ 'evenementen' ] === 'specifiek' )
                    {
                        include 'evenement/specifiek.php';
                    }
                    elseif( $_GET[ 'evenementen' ] === 'wijzigen' )
                    {
                        include 'evenement/wijzigen.php';
                    }
                }

            }
            ?>
        </div>
    </main>
    <!-- end content -->
</div>

<footer class="app-footer">
    <span><a href="https://www.windesheim.nl/">Windesheim</a> &copy; <?= date('Y'); ?> </span>
</footer>

<!-- Bootstrap and necessary plugins -->
<script src="public/js/jquery.min.js"></script>
<script src="public/js/propper.js"></script>
<script src="public/js/bootstrap.js"></script>
<script src="public/js/pace.js"></script>

<!-- Plugins and scripts required by all views -->
<script src="public/js/Chart.min.js"></script>

<!-- GenesisUI main scripts -->

<script src="public/js/app.js"></script>

<!-- Plugins and scripts required by this views -->

<!-- Custom scripts required by this view -->
<script src="public/js/main.js"></script>

</body>
</html>