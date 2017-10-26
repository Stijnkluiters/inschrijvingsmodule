<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 17:48
 */

function checkPassword($hashedOriginalPassword, $input)
{
    $options = ['cost' => 15];

    if(password_verify($input, $hashedOriginalPassword)){
        if (password_needs_rehash($hashedOriginalPassword, PASSWORD_DEFAULT,  $options)) {
            // If so, create a new hash, and replace the old one
            // TODO: how to know if user had an strong or default password?
            $newHash = generatePassword($input, false);
            // TODO: save newHash to the user
        }
    };
}
function generatePassword($input, $highSecurity = false)
{
    $options = ['cost' => 15];
    if($highSecurity) {
        return password_hash($input, PASSWORD_BCRYPT, $options);
    } else {
        return password_hash($input, PASSWORD_DEFAULT, $options);
    }
}
