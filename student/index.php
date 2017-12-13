<?php

include_once '../config.php';

$db = db();
$stmt = $db->query('select * from evenement');
$evenemten = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="../../favicon.ico">-->

    <title>Studenten evenementne overzicht</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= route('/public/css/style.css'); ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= route('/public/css/student.css'); ?>" rel="stylesheet">
</head>

<body>

<div class="collapse bg-inverse" id="navbarHeader">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 py-4">
                <h4 class="text-white">Contact</h4>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Follow on Twitter</a></li>
                    <li><a href="#" class="text-white">Like on Facebook</a></li>
                    <li><a href="#" class="text-white">Email me</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="navbar navbar-inverse bg-inverse">
    <div class="container d-flex justify-content-between">
        <a href="#" class="navbar-brand">Roc midden Nederland</a>

    </div>
</div>

<section class="jumbotron text-center img-responsive" style="background-image: url('<?= route("/public/img/logo.png"); ?>'); background-repeat: no-repeat; background-size: cover">
    <div class="container">
        <h1 class="jumbotron-heading">ROC midden Nederland</h1>
        <p class="lead text-muted">Evenementen overzicht van de student {studentnaam}</p>
    </div>
</section>

<div class="album text-muted">
    <div class="container">
        <?php foreach ($evenemten

        as $evenemnt) { ?>
        <div class="row">
            <div class="card col-12">
                <div class="card-body">
                    <h4 class="card-title"><?= ucfirst($evenemnt[ 'titel' ]); ?></h4>
                    <h5 class="text-muted"><?= ucfirst($evenemnt[ 'onderwerp' ]); ?></h5>

                    <p class="card-text"><?= ucfirst($evenemnt[ 'omschrijving' ]); ?></p>
                    <ul class="list-group">
                        <li class="list-group-item">startdatum: <?= date('Y-M-d H:i', strtotime($evenemnt[ 'begintijd' ])); ?></li>
                        <li class="list-group-item">einddatum: <?= date('Y-M-d H:i', strtotime($evenemnt[ 'eindtijd' ])); ?></li>
                    </ul>
                </div>
            </div>
            </div>
            <?php } ?>

    </div>
</div>

<footer class="text-muted">
    <div class="container">
        <p class="float-right">
            <a href="#">Back to top</a>
        </p>
        <p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
        <p>New to Bootstrap? <a href="../../">Visit the homepage</a> or read our <a href="../../getting-started/">getting
                started guide</a>.</p>
    </div>
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
        integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
        crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="https://v4-alpha.getbootstrap.com/assets/js/vendor/holder.min.js"></script>
<script>
    $(function () {
        Holder.addTheme("thumb", {background: "#55595c", foreground: "#eceeef", text: "Thumbnail"});
    });
</script>
<script src="https://v4-alpha.getbootstrap.com/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="https://v4-alpha.getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
