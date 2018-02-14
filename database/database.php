<?php

require __DIR__ . '/../config.php';



function db () {
    $db_name = db_name;
    $db_user = db_user;
    $db_pass = db_pass;




    try {
        $dbh = new PDO('mysql:host=localhost;dbname='.$db_name, $db_user, $db_pass,
            array(
            PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => false,
                PDO::ERRMODE_WARNING => false
        ));
        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}