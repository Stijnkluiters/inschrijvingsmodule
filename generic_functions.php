<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 22:14
 *
 * @param $url
 */


function redirect($url)
{
    if (!is_string($url)) {
        throw new Exception($url . ' is not an String you silly');
    }

    return header(sprintf('Location: %s', Projectroot . $url));
}

function filter_url($url)
{

    /*
     * How does it work?
     *
     *   In PHP you can get particular character of a string with array index notation.
     *  $url[0] is the first character of a string (if $url is a string).
     *
     */
    if ($url[0] !== '/') {
        $url .= '/';
    }

    return $url;
}

// return correct url for <ahref tags or stylesheet links
function route($url)
{
    return htmlspecialchars(Projectroot . $url);
}
/*
 * Create a random string
 * @param $length the length of the string to create
 * @return $str the string
 */
function randomString($length = 6) {
    $str = "";
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}
/**
 * @param $rolename
 * @return integer account_id from table account
 */
function generateRandomAccountForRole($username,$rolename)
{
    $rol_id = check_if_role_exists($rolename);
    $randomString = randomString(8);
    $randompassword = generatePassword(
        $randomString
    );

    $db = db();
    $stmt = $db->prepare('insert into account (gebruikersnaam, wachtwoord, rol_id) VALUES (
              ?,?,?
            )');
    $stmt->execute(array(
       $username,
        $randompassword,
        $rol_id
    ));
    return $db->lastInsertId();


}

function startsession()
{

    if(!isset($_SESSION)) {
       session_start();
    }
}

function dump($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}
function success($msg) {
    return print(
        '<div class="alert alert-success" role="alert">
          <strong>Success!</strong> '.$msg.'
        </div>'
    );
}
function error($msg) {
    return print('<div class="alert alert-danger" role="alert">
  <strong>Error!</strong> '.$msg.'
</div>');
}
