<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 26-10-2017
 * Time: 22:14
 *
 * @param $url
 */


function redirect($url, $message = null)
{
    if (isset($message)) {

        startsession();
        $_SESSION['message'] = $message;

    }
    if (!is_string($url)) {
        throw new Exception($url . ' is not an String you silly');
    }
    if (headers_sent()) {
        echo '<script> location.replace("' . Projectroot . $url . '"); </script>';
    } else {
        return header(sprintf('Location: %s', Projectroot . $url));
    }
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
function randomString($length = 6)
{

    $str = "";
    $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }

    return $str;
}

/**
 * @param $rolename
 *
 * @return integer account_id from table account
 */
function generateRandomAccountForRole($username, $rolename)
{

    $rol_id = check_if_role_exists($rolename);

    $password = randomString(8);

    $randompassword = generatePassword(
        $password
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
    $subject = 'Het ROC midden Nederland inschrijvingsmodule';
    // message variable.
    include_once 'mail/leerling_account.php';

    sendMail($username . '@edu.rocmn.nl', $subject, $message);

    return $db->lastInsertId();


}

function startsession()
{

    if (!isset($_SESSION)) {
        session_start();
    }
}

function dump()
{
    $arr = func_get_args();
    echo "<pre>";
    foreach ($arr as $value) {
        var_dump($value);
    }
    echo "</pre>";
}

function success($msg)
{
    return print(
        '<div class="alert alert-success" role="alert">
          <strong>Success!</strong> ' . $msg . '
        </div>'
    );
}

function error($msg)
{
    return print('<div class="alert alert-danger" role="alert">
  <strong>Error!</strong> ' . $msg . '
</div>');
}
