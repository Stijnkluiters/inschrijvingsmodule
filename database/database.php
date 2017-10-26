<?php

require __DIR__ . '\..\config.php';

try {
    $dbh = new PDO('mysql:host=localhost;dbname='.$db_name, $db_user, $db_pass,array(
        PDO::ATTR_PERSISTENT => true
    ));
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

return $dbh;
