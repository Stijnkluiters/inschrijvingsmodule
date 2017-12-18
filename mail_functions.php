<?php


/** this function is used to send a mail without configuration, so it's easy to call whenever needed. */
function sendMail($reciever,$subject,$message)
{
    // create mail functionality
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    //Server settings
    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
    //$mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = mailhost;                                 // Specify main and backup SMTP servers
    $mail->SMTPAuth = mailSMTP;                               // Enable SMTP authentication
    $mail->Username = mailuser;                              // SMTP username
    $mail->Password = mailpassword;                           // SMTP password
    $mail->SMTPSecure = mailSMTPSecure;                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = mailPort;                                    // TCP port to connect to
    $mail->setFrom(mailFromEmail, mailFromUser);
    $mail->addReplyTo(mailFromEmail, mailFromUser);
    $mail->addBCC(mailFromEmail);

    $mail->Subject = $subject;
    $mail->Body = $message;
    // check if receiver is array.
    if(is_array($reciever)) {
        foreach ($reciever as $value) {
            $mail->addAddress($value);
        }
    } else {
        $mail->addAddress($reciever);
    }
    $mail->send();
}