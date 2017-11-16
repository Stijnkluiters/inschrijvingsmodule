<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 16-11-2017
 * Time: 13:29
 */
include 'config.php';

if(isset($_POST['submit'])) {
    $username = $_POST['gebruikersnaam'];
    $password = $_POST['wachtwoord'];


    $db = db();
    $stmt = $db->prepare('SELECT * FROM gebruiker WHERE gebruiker.gebruikersnaam = :gebruikersnaam');
    $stmt->bindParam(':gebruikersnaam', $username);
    $stmt->execute();
    $gebruikers = $stmt->fetchAll(PDO::FETCH_BOTH);

    var_dump($gebruikers);
    exit;
}

?>
<html>
    <head>
        <link href="<?= route('/public/css/login.css') ?>" rel="stylesheet"/>
        <link href="<?= route('/public/css/style.css') ?>" rel="stylesheet"/>
    </head>
    <body>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pr-wrap">
                    <div class="pass-reset">
                        <label>
                            Enter the email you signed up with</label>
                        <input type="email" placeholder="Email" />
                        <input type="submit" value="Submit" class="pass-reset-submit btn btn-success btn-sm" />
                    </div>
                </div>
                <div class="wrap">
                    <p class="form-title">
                        Sign In</p>
                    <form method="post" action="<?= route('/login.php'); ?>" class="login">
                        <input type="text" placeholder="Username" name="gebruikersnaam" />
                        <input type="password" placeholder="Password" name="wachtwoord" />
                        <input type="submit" value="Sign In" name="submit" class="btn btn-success btn-sm" />
                        <div class="remember-forgot">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" />
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 forgot-pass-content">
                                    <a href="javascription:void(0)" class="forgot-pass">Forgot Password</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="posted-by">Posted By: <a href="http://www.jquery2dotnet.com">Bhaumik Patel</a></div>
    </div>



    </body>
</html>