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


function get_user_info($account = null)
{

    startsession();
    $db = db();
    if( $account === null )
    {
        $account_id = $_SESSION[ authenticationSessionName ];
    }
    else
    {
        $account_id = $account[ 'account_id' ];
    }

    $rolnaam = get_account_his_role($account_id);
    if( $rolnaam !== null )
    {
        $rolnaam = $rolnaam[ 'rolnaam' ];
        switch ($rolnaam)
        {
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
        $stmt->bindParam('account_id', $account_id);
        $stmt->execute();

        return $stmt->fetch();
    }
    else
    {
        throw new \Exception('Er zijn geen resultaten gevonden voor account zijn rol.');
    }

}

/**
 * @param null $evenement_id
 * @param null $leerlingnummer
 * @param bool $whitelisted
 * @param bool $update
 *
 * @throws Exception
 *
 *
 * This is used to link every event to every student. Many to Many relationship.
 * OR if and argument is given it will specificly only use that ID.
 *  THIS FUNCTION DOES NOT CHECK IF EVENT OR STUDENT EXISTS!
 */
function linkStudentstoEvents($evenement_id = null, $leerlingnummer = null, $whitelisted = false, $update = false)
{
    $db = db();
    if( $evenement_id === null )
    {
        $stmt = $db->prepare('select evenement_id from evenement where status = ? and begintijd > ?');
        $stmt->execute(array(
            0,
            date('Y-m-d H:i')
        ));
        $evenement_id = $stmt->fetchAll();
    }
    if( !is_int($whitelisted) )
    {
        throw new Exception('Whitelisted is geen nummerieke waarde.');
    }


    // 1 specefiek evenement moet gekoppeld worden aan 1 of meerdere studenten.
    if( is_int($evenement_id) )
    {
        chainEventWithLeerlingen($evenement_id, $leerlingnummer, $whitelisted, $update);
    }
    else
    {
        // meerdere evenementen moeten gekoppeld worden aan 1 of meerdere studenten.
        foreach ($evenement_id as $evenement)
        {
            $evenement_id = $evenement[ 'evenement_id' ];
            chainEventWithLeerlingen($evenement_id, $leerlingnummer, $whitelisted, $update);
        }
    }
}

/**
 * @param      $evenement_id
 * @param      $leerlingnummer
 * @param      $whitelisted
 * @param bool $update
 *
 * This function is used as a private function, can be called from the function: linkStudentstoEvents
 *
 */
function chainEventWithLeerlingen($evenement_id, $leerlingnummer, $whitelisted, $update = false)
{

    $db = db();
    if( is_int($leerlingnummer) )
    {

        // koppelen, specifiek 1 evenement en leerlingnummer!
        chainEventToLeerling($evenement_id, $leerlingnummer, $whitelisted, $update);
    }
    else
    {
        // meerdere leerlingen moeten gekoppeld worden aan één evenement.
        $stmt = $db->prepare('select leerlingnummer from leerling where deleted = ?');
        $stmt->execute(array( 0 ));
        $leerlingnummer = $stmt->fetchAll();
        foreach ($leerlingnummer as $leerling)
        {

            $leerlingnummer = $leerling[ 'leerlingnummer' ];
            chainEventToLeerling($evenement_id, $leerlingnummer, $whitelisted, $update);
        }

    }
}

/**
 * @param      $evenement_id
 * @param      $leerlingnummer
 * @param      $whitelisted
 * @param bool $update
 *
 * @return bool
 *
 *
 * This function is used as a private function, can be called from the function: chainEventWithLeerlingen
 *
 */
function chainEventToLeerling($evenement_id, $leerlingnummer, $whitelisted, $update = true)
{

    // check if leerling is already chained to event.
    $db = db();
    $stmt = $db->prepare('select evenement_id from inschrijving where leerlingnummer = ? and evenement_id = ?');
    $stmt->execute(array(
        $evenement_id,
        $leerlingnummer
    ));
    $results = $stmt->rowCount();
    // update user with new whitelisted status
    if( $results > 0 && $update === true )
    {
        $stmt = $db->prepare('UPDATE `inschrijving` 
        SET 
        `gewhitelist`= ?
         WHERE evenement_id = ? and leerlingnummer = ?');

        return $stmt->execute(array(
            $whitelisted, $evenement_id, $leerlingnummer
        ));
    }
    else
    {
        // insert user with whitelist status.
        $stmt = $db->prepare('INSERT INTO `inschrijving`(`leerlingnummer`, `evenement_id`, `gewhitelist`) VALUES (?,?,?)');
        return $stmt->execute(array($leerlingnummer, $evenement_id, $whitelisted));
    }

}

