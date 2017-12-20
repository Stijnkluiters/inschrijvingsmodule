<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 30/11/2017
 * Time: 12:05
 */
$afkorting = filter_var(filter_input(INPUT_GET,'afkorting',FILTER_SANITIZE_STRING),FILTER_VALIDATE_INT);;

$db = db();
$docentQuery = $db->prepare('SELECT * FROM medewerker WHERE afkorting = :afkorting');
$docentQuery->bindParam('afkorting',$afkorting, PDO::PARAM_STR);
$docentQuery->execute();
$docent = $docentQuery->fetch();

if($docent['deleted'] == false){
    $stmt = $db->prepare('
            UPDATE medewerker SET
            deleted = true
            WHERE afkorting = :afkorting');

    $stmt->bindParam('afkorting', $afkorting, PDO::PARAM_STR);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtmedewerker', $docent['roepnaam'].' is geactiveerd');
} else {
    $stmt = $db->prepare('
            UPDATE medewerker SET
            deleted = false
            WHERE afkorting = :afkorting');

    $stmt->bindParam('afkorting', $afkorting, PDO::PARAM_STR);
    $stmt->execute();
    redirect('/index.php?gebruiker=overzichtmedewerker' , $docent['roepnaam'] . ' is gedeactiveerd');
}

?>
