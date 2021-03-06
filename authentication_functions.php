<?php

/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 17:48
 */
require_once 'config.php';

const passwordAlgo = PASSWORD_BCRYPT;

const options = [ 'cost' => 11 ];

const authenticationSessionName = 'account';
// controleren of een ingelogde gebruiker ingelogd is.
function check_logged_in_user()
{

    startsession();
    // check if user is still logged in; check if user that is logged in is still in database.
    if( !isset($_SESSION[ authenticationSessionName ]) )
    {
        logout();
        redirect('/login.php', 'U bent uitgelogd.');
    }
    // check if current user still exists in database.
    $db = db();
    $stmt = $db->prepare('select * from account where account_id = :account_id');
    $stmt->bindParam('account_id', $_SESSION[ authenticationSessionName ], PDO::PARAM_INT);
    $stmt->execute();
    if( !$stmt->rowCount() )
    {
        logout();
        redirect('/login.php', 'U bent uitgelogd');
    }

    $fingerprint = 'ROCMNROCKS';
    // this doesn't work behind loadbalancing.
    $encryptedfingerprint = md5($_SERVER[ 'REMOTE_ADDR' ] . $fingerprint . $_SERVER[ 'HTTP_USER_AGENT' ]);

    if( !isset($_SESSION[ 'fingerprint' ]) )
    {
        $_SESSION[ 'fingerprint' ] = $encryptedfingerprint;
    }
    // check if fingerprint is equal to the older fingerprint, this way we can ensure it's the same user
    if( $_SESSION[ 'fingerprint' ] != $encryptedfingerprint )
    {
        logout();
        redirect('/login.php', 'u bent uigelogd');
    }

}
// controleren of het wachtwoord correct is.
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
            $stmt = $dbh->prepare('UPDATE account SET wachtwoord = :wachtwoord WHERE account_id = :id');
            $stmt->bindParam('wachtwoord', $newHash, PDO::PARAM_STR);
            $stmt->bindParam('id', $originalID, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return true;
    }

    return false;

}
// genereren van een hashed wachtwoord
function generatePassword($input)
{

    return password_hash($input, passwordAlgo, options);
}

// This function does not check for wrong user input, so it can be used anywhere.
// Inlog functie zodat je op meerdere plekken kunt inloggen.
function login($username, $password)
{

    $dbh = db();
    $stmt = $dbh->prepare('SELECT account_id, wachtwoord, timestried FROM account WHERE gebruikersnaam = :username');
    $stmt->bindParam('username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if( count($result) > 0 )
    {
        // check if user isnt deleted.
        $user = get_user_info($result);
        if( $user[ 'deleted' ] === "1" )
        {
            return 'USERDELETED';
        }

        if( checkPassword($result[ 'wachtwoord' ], $password, intval($result[ 'account_id' ])) )
        {

            startsession();
            // delete old session: true
            session_regenerate_id(true);
            $_SESSION[ authenticationSessionName ] = $result[ 'account_id' ];


            $stmt = $dbh->prepare('update account set timestried = 0 where gebruikersnaam = :username');
            $stmt->bindParam('username', $username);
            $stmt->execute();


            return $_SESSION[ authenticationSessionName ];
        }
        else
        {
            // controleren hoevaak een gebruiker heeft geprobeerd om in te loggen
            if( $result[ 'timestried' ] > 2 )
            {

                deactivateAccount($result[ 'account_id' ]);
                return 'USERDELETED';
            }
            else
            {

                $stmt = $dbh->prepare('update account set timestried = timestried + 1 where gebruikersnaam = :username');
                $stmt->bindParam('username', $username);
                $stmt->execute();
            }

            return 'INVALIDPASSWORD';
        }
    }
    else
    {
        return 'NOUSER';
    }

}

// uitlog functie
function logout ()
{

    startsession();
    session_regenerate_id(true);
    unset($_SESSION[ authenticationSessionName ]);

    return true;
}

// controleren of een rol bestaat, zo niet. wordt hij toegevoegd
function check_if_role_exists($rolnaam)
{

    $db = db();
    $stmt = $db->prepare('SELECT rolid as id FROM rolnaam WHERE rolnaam = ?');
    $stmt->execute(array( $rolnaam ));
    $id = $stmt->fetch();
    if( $id === false || empty($id) )
    {
        $stmt = $db->prepare('INSERT INTO rolnaam (rolnaam) VALUES (?)');
        $stmt->execute(array( $rolnaam ));

        return $db->lastInsertId();
    }
    $id = $id[ 'id' ];

    return $id;
}

// pak afhankelijk van account_id de rol
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

// Maak de gebruikersnaam netjes en geef het terug
function formatusername($account = null) {

    if( $account === null )
    {
        $account = get_user_info();
    }
    $gebruikernaam = '';
    if( !empty($account[ 'roepnaam' ]) )
    {
        $gebruikernaam = ucfirst($account[ 'roepnaam' ]) . ' ';
    }
    if( !empty($account[ 'tussenvoegsel' ]) )
    {
        $gebruikernaam .= $account[ 'tussenvoegsel' ] . ' ';
    }
    if( !empty($account[ 'achternaam' ]) )
    {
        $gebruikernaam .= ucfirst($account[ 'achternaam' ]);
    }

    return $gebruikernaam;
}


