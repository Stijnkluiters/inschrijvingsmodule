<?php

require __DIR__ . '\..\config.php';



function db () {
    $db_name = '';
    $db_user = '';
    $db_pass = '';


    try {
        $dbh = new PDO('mysql:host=localhost;dbname='.$db_name, $db_user, $db_pass,array(
            PDO::ATTR_PERSISTENT => true
        ));
        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}