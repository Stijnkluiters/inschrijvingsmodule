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
    // TODO: correct query with correct details
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




