<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 2-12-2017
 * Time: 11:13
 */
//
//function delete_medewerker($afkorting)
//{
//    // also delete all underneath records
//    $db = db();
//    $stmt = $db->prepare('delete from medewerker where afkorting = :afkorting');
//    $stmt->bindParam('afkorting',$afkorting);
//    return $stmt->execute();
//}


// haalt alle gegevens op op basis van je rol, medewerker, leerling of contact persoon.



function get_user_info($account = null) {
    startsession();
    $db = db();
    if($account === null) {
        $account_id = $_SESSION[authenticationSessionName];
    } else {
        $account_id = $account['account_id'];
    }

    $rolnaam = get_account_his_role($account_id);
    if($rolnaam !== null) {
        $rolnaam = $rolnaam['rolnaam'];
        switch ($rolnaam) {
            case 'beheerder':
            case "docent":
                $sql = 'SELECT * FROM account a JOIN rolnaam r ON a.rol_id = r.rolid LEFT JOIN medewerker m ON a.account_id = m.account_id';
                break;
            case "leerling":
                $sql = 'SELECT * FROM account a  JOIN rolnaam r ON a.rol_id = r.rolid LEFT JOIN leerling l ON a.account_id = l.account_id';
                break;
            case "externbedrijf":
                $sql = 'SELECT * FROM account a JOIN rolnaam r ON a.rol_id = r.rolid LEFT JOIN contactpersoon c ON a.account_id = c.account_id';
                break;
            default :
                $sql = 'SELECT * FROM account a JOIN rolnaam r ON a.rol_id = r.rolid ';
                // alleen account, omdat er geen resultaten gevonden extra zijn.
                break;
        }
        $sql .= ' WHERE a.account_id = :account_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam('account_id',$account_id);
        $stmt->execute();
        return $stmt->fetch();
    } else {
        throw new \Exception('Er zijn geen resultaten gevonden voor account zijn rol.');
    }

}
