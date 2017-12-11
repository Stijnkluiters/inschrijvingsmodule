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
        exit;
    }
    if(headers_sent()) {
        echo '<script> location.replace("'.Projectroot . $url.'"); </script>';
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


    // create mail functionality
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = mailhost;  // Specify main and backup SMTP servers
    $mail->SMTPAuth = mailSMTP;                               // Enable SMTP authentication
    $mail->Username = mailuser;                 // SMTP username
    $mail->Password = mailpassword;                           // SMTP password
    $mail->SMTPSecure = mailSMTPSecure;                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = mailPort;                                    // TCP port to connect to
    $mail->setFrom(mailFromEmail, mailFromUser);
    $mail->addReplyTo(mailFromEmail, mailFromUser);
    $mail->addBCC(mailFromEmail);

    //Recipients
    $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress('ellen@example.com');               // Name is optional

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

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
