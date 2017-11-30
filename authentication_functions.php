<?php

/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 17:48
 */
require_once 'config.php';

const passwordAlgo = PASSWORD_BCRYPT;

const options = [ 'cost' => 15 ];

const authenticationSessionName = 'account';

function checkPassword($hashedOriginalPassword, $input, $originalID)
{

    if( !is_int($originalID) )
    {
        throw new \Exception('Original ID is not an integer which can`t be queried, this is an value off the user which is being checked.');
    }

    if( password_verify($input, $hashedOriginalPassword) )
    {
        if( password_needs_rehash($hashedOriginalPassword, passwordAlgo, options) )
        {
            $newHash = generatePassword($input);

            $dbh = db();
            // TODO: correct query with correct details
            $stmt = $dbh->prepare('UPDATE gebruiker SET password = :password WHERE id = :ID');
            $stmt->bindParam('password', $newHash, PDO::PARAM_STR);
            $stmt->bindParam('id', $originalID, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return true;
    }

    return false;

}

function generatePassword($input)
{

    return password_hash($input, passwordAlgo, options);
}

// This function does not check for wrong user input, so it can be used anywhere.
function login($username, $password)
{

    $dbh = db();
    $stmt = $dbh->prepare('SELECT account_id, wachtwoord FROM account WHERE gebruikersnaam = :username');
    $stmt->bindParam('username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if( count($result) > 0 )
    {
        if( checkPassword($result['wachtwoord'], $password, intval($result[ 'account_id' ])) )
        {
            startsession();
            $_SESSION[ authenticationSessionName ] = $result[ 'account_id' ];

            return $_SESSION[ authenticationSessionName ];
        }
        else
        {
            return 'INVALIDPASSWORD';
        }
    }
    else
    {
        return 'NOUSER';
    }

}
function logout ()
{
    startsession();
    unset($_SESSION[authenticationSessionName]);
    return true;
}

function check_if_role_exists($rolnaam)
{
    $db = db();
    $stmt = $db->prepare('SELECT rolid as id FROM rolnaam WHERE rolnaam = ?');
    $stmt->execute(array($rolnaam));
    $id = $stmt->fetch();
    if($id === false || empty($id)) {
        $stmt = $db->prepare('INSERT INTO rolnaam (rolnaam) VALUES (?)');
        $stmt->execute(array($rolnaam));
        return $db->lastInsertId();
    }
    $id = $id['id'];
    return $id;
}


function get_account_his_role($account_id)
{
    $db = db();

    $stmt = $db->prepare('
        select * from rolnaam WHERE rolid = (select rol_id from account where account_id = :account_id)
    ');
    $stmt->bindParam(':account_id', $account_id);
    $stmt->execute();
    return $stmt->fetch();
}
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
                $sql = 'SELECT * FROM account a JOIN medewerker m ON a.account_id = m.account_id';
            break;
            case "leerling":
                $sql = 'SELECT * FROM account a JOIN leerling l ON a.account_id = l.account_id';
                break;
            case "contactpersoon":
                $sql = 'SELECT * FROM account a JOIN contactpersoon c ON a.account_id = c.account_id';
            break;
            default :
                $sql = 'SELECT * FROM account a';
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


function formatusername($account = null) {
    if($account === null) {
        $account = get_user_info();
    }
    $gebruikernaam = ucfirst($account['roepnaam']) . ' ';
    if(!empty($account['voorvoegsel'])) {
        $gebruikernaam .= $account['voorvoegsel']  . ' ';
    }
    $gebruikernaam .= ucfirst($account['achternaam']);

    return $gebruikernaam;
}


