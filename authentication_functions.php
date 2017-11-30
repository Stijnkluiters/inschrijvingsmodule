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

const authenticationSessionName = 'User';

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
    $stmt = $dbh->prepare('SELECT id, wachtwoord FROM gebruiker WHERE gebruikersnaam = :username');
    $stmt->bindParam('username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if( count($result) > 0 )
    {
        if( checkPassword($result['wachtwoord'], $password, intval($result[ 'id' ])) )
        {
            startsession();
            $_SESSION[ authenticationSessionName ] = $result[ 'id' ];

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
    $stmt = $db->prepare('SELECT id FROM rol WHERE naam = ?');
    $stmt->execute(array($rolnaam));
    $id = $stmt->fetch();
    if($id !== false) {

        $rol_id = $id['id'];


        $stmt = $db->prepare('INSERT INTO rol (naam, created_at, updated_at) VALUES (?,?,null)');
        $stmt->execute(array($rolnaam,date('Y-m-d')));
        return $db->lastInsertId();
    }
    return $id;
}

function connect_user_to_role($rolename,$user_id)
{

    check_if_role_exists($rolename);

    $db = db();

    $stmt = $db->prepare('select id from rol where naam = :naam');
    $stmt->bindParam('naam', $rolename);
    $stmt->execute();
    $rol_id = $stmt->fetch();
    if($rol_id) {
        $stmt = $db->prepare('INSERT INTO gebruiker_heeft_rol (rol_id, gebruiker_id) VALUES (:role_id,:user_id)');
        $stmt->bindParam('role_id',$rol_id['id'],PDO::PARAM_STR);
        $stmt->bindParam('user_id',$user_id,PDO::PARAM_STR);
        $stmt->execute();

    }
    return $rol_id;

}
function check_if_user_has_role($rolename,$user_id)
{
    $db = db();

    $stmt = $db->prepare('select id from rol where naam = :naam');
    $stmt->bindParam(':naam', $rolename);
    $stmt->execute();
    $rol_id = $stmt->fetch()['id'];

    $stmt = $db->prepare('SELECT count(*) FROM gebruiker_heeft_rol WHERE 
      gebruiker_id = :gebruiker_id AND rol_id = :rol_id');
    $stmt->bindParam(':rol_id',$rol_id,PDO::PARAM_INT);
    $stmt->bindParam(':gebruiker_id',$user_id,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount() > 0;

}
function AuthUserDetails() {
    startsession();
    $db = db();
    $stmt = $db->prepare('select * from gebruiker g left join adres a on a.id = g.adres_id where g.id = :id');
    $stmt->bindParam('id',$_SESSION[authenticationSessionName]);
    $stmt->execute();
    return $stmt->fetch();
}
function formatusername($user = null) {
    if($user === null) {
        $user = AuthUserDetails();
    }
    $gebruikernaam = ucfirst($user['roepnaam']) . ' ';
    if(!empty($user['voorvoegsel'])) {
        $gebruikernaam .= $user['voorvoegsel']  . ' ';
    }
    $gebruikernaam .= ucfirst($user['achternaam']);
    return $gebruikernaam;
}


