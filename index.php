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





include_once 'config.php';
startsession();

if(!isset($_SESSION[authenticationSessionName])) {
    redirect('login.php');
}


?>
<!--
 * CoreUI - Open Source Bootstrap Admin Template
 * @version v1.0.2
 * @link http://coreui.io
 * Copyright (c) 2017 creativeLabs Łukasz Holeczek
 * @license MIT
 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword"
          content="Bootstrap,Admin,Template,Open,Source,AngularJS,Angular,Angular2,Angular 2,Angular4,Angular 4,jQuery,CSS,HTML,RWD,Dashboard,React,React.js,Vue,Vue.js">
    <title>Inschrijfmodule</title>

    <!-- Icons -->
    <link href="public/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Main styles for this application -->
    <link href="public/css/style.css" rel="stylesheet">
    <!-- Styles required by this views -->
</head>

<!-- BODY options, add following classes to body to change options

// Header options
1. '.header-fixed'					- Fixed Header

// Brand options
1. '.brand-minimized'       - Minimized brand (Only symbol)

// Sidebar options
1. '.sidebar-fixed'					- Fixed Sidebar
2. '.sidebar-hidden'				- Hidden Sidebar
3. '.sidebar-off-canvas'		- Off Canvas Sidebar
4. '.sidebar-minimized'			- Minimized Sidebar (Only icons)
5. '.sidebar-compact'			  - Compact Sidebar

// Aside options
1. '.aside-menu-fixed'			- Fixed Aside Menu
2. '.aside-menu-hidden'			- Hidden Aside Menu
3. '.aside-menu-off-canvas'	- Off Canvas Aside Menu

// Breadcrumb options
1. '.breadcrumb-fixed'			- Fixed Breadcrumb

// Footer options
1. '.footer-fixed'					- Fixed footer

-->

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>

    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            <a class="nav-link" href="<?= route('/index.php'); ?>">Dashboard</a>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link" href="<?= route('/index.php?gebruiker=alles'); ?>">Users</a>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link" href="#">Settings</a>
        </li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-bell"></i><span
                        class="badge badge-pill badge-danger">5</span></a>
        </li>
        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-list"></i></a>
        </li>
        <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="true" aria-expanded="false">
                <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                <span class="d-md-down-none">admin</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <strong>Account</strong>
                </div>
                <a class="dropdown-item" href="#"><i class="fa fa-bell-o"></i> Updates<span
                            class="badge badge-info">42</span></a>
                <a class="dropdown-item" href="#"><i class="fa fa-envelope-o"></i> Messages<span
                            class="badge badge-success">42</span></a>
                <a class="dropdown-item" href="#"><i class="fa fa-tasks"></i> Tasks<span
                            class="badge badge-danger">42</span></a>
                <a class="dropdown-item" href="#"><i class="fa fa-comments"></i> Comments<span
                            class="badge badge-warning">42</span></a>
                <div class="dropdown-header text-center">
                    <strong>Settings</strong>
                </div>
                <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
                <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>
                <a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="badge badge-secondary">42</span></a>
                <a class="dropdown-item" href="#"><i class="fa fa-file"></i> Projects<span class="badge badge-primary">42</span></a>
                <div class="divider"></div>
                <a class="dropdown-item" href="#"><i class="fa fa-shield"></i> Lock Account</a>
                <a class="dropdown-item" href="<?= route('/logout.php') ?>"><i class="fa fa-lock"></i> Logout</a>
            </div>
        </li>
    </ul>
    <button class="navbar-toggler aside-menu-toggler" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>

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
                    <a class="nav-link" href="<?= route('/index.php?gebruiker=alles'); ?>">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        Gebruikers
                        <span class="badge badge-primary">NEW</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="charts.html"><i class="icon-pie-chart"></i> Charts</a>
                </li>
                <li class="divider"></li>
                <li class="nav-title">
                    Extras
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-star"></i> Pages</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="pages-login.html" target="_top"><i class="icon-star"></i>
                                Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages-register.html" target="_top"><i class="icon-star"></i>
                                Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages-404.html" target="_top"><i class="icon-star"></i> Error 404</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages-500.html" target="_top"><i class="icon-star"></i> Error 500</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>

    <!-- Main content -->
    <main class="main">

        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>

            <!-- Breadcrumb Menu-->
            <li class="breadcrumb-menu d-md-down-none">
                <div class="btn-group" role="group" aria-label="Button group">
                    <a class="btn" href="#"><i class="icon-speech"></i></a>
                    <a class="btn" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                    <a class="btn" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
                </div>
            </li>
        </ol>
        <!-- start content -->
        <div class="container-fluid">
            <?php


            if( count($_GET) === 0 )
            {
                include 'dashboard.php';
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
                    elseif( $_GET[ 'gebruiker' ] === 'invoerendocent' )
                    {
                        require 'beheerder/gebruikers/insertdocent.php';
                    }
                    else
                    {
                        exit;
                        // specifieke gebruiker
                    }
                }

                // evenementen
                if( isset($_GET[ 'evenement' ]) )
                {
                    if( $_GET[ 'evenement' ] === 'alles' )
                    {

                        // alle evenementen
                    }
                    else
                    {
                        // specifiek evenement
                    }
                }


            }
            ?>
        </div>
        <!-- end content -->
        <aside class="aside-menu">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab"><i
                                class="icon-list"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><i class="icon-speech"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="icon-settings"></i></a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="timeline" role="tabpanel">
                    <div class="callout m-0 py-2 text-muted text-center bg-light text-uppercase">
                        <small><b>Today</b></small>
                    </div>
                    <hr class="transparent mx-3 my-0">
                    <div class="callout callout-warning m-0 py-3">
                        <div class="avatar float-right">
                            <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div>Meeting with
                            <strong>Lucas</strong>
                        </div>
                        <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small>
                        <small class="text-muted"><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA</small>
                    </div>
                    <hr class="mx-3 my-0">
                    <div class="callout callout-info m-0 py-3">
                        <div class="avatar float-right">
                            <img src="img/avatars/4.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div>Skype with
                            <strong>Megan</strong>
                        </div>
                        <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 4 - 5pm</small>
                        <small class="text-muted"><i class="icon-social-skype"></i>&nbsp; On-line</small>
                    </div>
                    <hr class="transparent mx-3 my-0">
                    <div class="callout m-0 py-2 text-muted text-center bg-light text-uppercase">
                        <small><b>Tomorrow</b></small>
                    </div>
                    <hr class="transparent mx-3 my-0">
                    <div class="callout callout-danger m-0 py-3">
                        <div>New UI Project -
                            <strong>deadline</strong>
                        </div>
                        <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 10 - 11pm</small>
                        <small class="text-muted"><i class="icon-home"></i>&nbsp; creativeLabs HQ</small>
                        <div class="avatars-stack mt-2">
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/2.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/3.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/4.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/5.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                        </div>
                    </div>
                    <hr class="mx-3 my-0">
                    <div class="callout callout-success m-0 py-3">
                        <div>
                            <strong>#10 Startups.Garden</strong> Meetup
                        </div>
                        <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small>
                        <small class="text-muted"><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA</small>
                    </div>
                    <hr class="mx-3 my-0">
                    <div class="callout callout-primary m-0 py-3">
                        <div>
                            <strong>Team meeting</strong>
                        </div>
                        <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 4 - 6pm</small>
                        <small class="text-muted"><i class="icon-home"></i>&nbsp; creativeLabs HQ</small>
                        <div class="avatars-stack mt-2">
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/2.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/3.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/4.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/5.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                            <div class="avatar avatar-xs">
                                <img src="img/avatars/8.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            </div>
                        </div>
                    </div>
                    <hr class="mx-3 my-0">
                </div>
                <div class="tab-pane p-3" id="messages" role="tabpanel">
                    <div class="message">
                        <div class="py-3 pb-5 mr-3 float-left">
                            <div class="avatar">
                                <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <span class="avatar-status badge-success"></span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Lukasz Holeczek</small>
                            <small class="text-muted float-right mt-1">1:52 PM</small>
                        </div>
                        <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod
                            tempor incididunt...
                        </small>
                    </div>
                    <hr>
                    <div class="message">
                        <div class="py-3 pb-5 mr-3 float-left">
                            <div class="avatar">
                                <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <span class="avatar-status badge-success"></span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Lukasz Holeczek</small>
                            <small class="text-muted float-right mt-1">1:52 PM</small>
                        </div>
                        <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod
                            tempor incididunt...
                        </small>
                    </div>
                    <hr>
                    <div class="message">
                        <div class="py-3 pb-5 mr-3 float-left">
                            <div class="avatar">
                                <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <span class="avatar-status badge-success"></span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Lukasz Holeczek</small>
                            <small class="text-muted float-right mt-1">1:52 PM</small>
                        </div>
                        <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod
                            tempor incididunt...
                        </small>
                    </div>
                    <hr>
                    <div class="message">
                        <div class="py-3 pb-5 mr-3 float-left">
                            <div class="avatar">
                                <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <span class="avatar-status badge-success"></span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Lukasz Holeczek</small>
                            <small class="text-muted float-right mt-1">1:52 PM</small>
                        </div>
                        <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod
                            tempor incididunt...
                        </small>
                    </div>
                    <hr>
                    <div class="message">
                        <div class="py-3 pb-5 mr-3 float-left">
                            <div class="avatar">
                                <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <span class="avatar-status badge-success"></span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Lukasz Holeczek</small>
                            <small class="text-muted float-right mt-1">1:52 PM</small>
                        </div>
                        <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod
                            tempor incididunt...
                        </small>
                    </div>
                </div>
                <div class="tab-pane p-3" id="settings" role="tabpanel">
                    <h6>Settings</h6>

                    <div class="aside-options">
                        <div class="clearfix mt-4">
                            <small><b>Option 1</b></small>
                            <label class="switch switch-text switch-pill switch-success switch-sm float-right">
                                <input type="checkbox" class="switch-input" checked="">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                        <div>
                            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </small>
                        </div>
                    </div>

                    <div class="aside-options">
                        <div class="clearfix mt-3">
                            <small><b>Option 2</b></small>
                            <label class="switch switch-text switch-pill switch-success switch-sm float-right">
                                <input type="checkbox" class="switch-input">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                        <div>
                            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </small>
                        </div>
                    </div>

                    <div class="aside-options">
                        <div class="clearfix mt-3">
                            <small><b>Option 3</b></small>
                            <label class="switch switch-text switch-pill switch-success switch-sm float-right">
                                <input type="checkbox" class="switch-input">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>

                    <div class="aside-options">
                        <div class="clearfix mt-3">
                            <small><b>Option 4</b></small>
                            <label class="switch switch-text switch-pill switch-success switch-sm float-right">
                                <input type="checkbox" class="switch-input" checked="">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>

                    <hr>
                    <h6>System Utilization</h6>

                    <div class="text-uppercase mb-1 mt-4">
                        <small><b>CPU Usage</b></small>
                    </div>
                    <div class="progress progress-xs">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">348 Processes. 1/4 Cores.</small>

                    <div class="text-uppercase mb-1 mt-2">
                        <small><b>Memory Usage</b></small>
                    </div>
                    <div class="progress progress-xs">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">11444GB/16384MB</small>

                    <div class="text-uppercase mb-1 mt-2">
                        <small><b>SSD 1 Usage</b></small>
                    </div>
                    <div class="progress progress-xs">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">243GB/256GB</small>

                    <div class="text-uppercase mb-1 mt-2">
                        <small><b>SSD 2 Usage</b></small>
                    </div>
                    <div class="progress progress-xs">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 10%" aria-valuenow="10"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">25GB/256GB</small>
                </div>
            </div>
        </aside>

</div>

<footer class="app-footer">
    <span><a href="http://coreui.io">CoreUI</a> © 2017 creativeLabs.</span>
    <span class="ml-auto">Powered by <a href="http://coreui.io">CoreUI</a></span>
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