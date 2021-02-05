<?php
require 'PHPMailer/PHPMailerAutoload.php';
require 'PHPMailer/class.phpmailer.php';

$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'yoann.bettinelli@gmail.com';
$mail->Password = 'darco180587';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
// $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;

$mail->setFrom('yoann.bettinelli@gmail.com', 'Yoann');
$mail->addAddress('yoann1805@gmail.com');

$mail-> isHTML(true);

$mail->Subject = "Cet email est un test";
$mail->Body = "Afin de valider votre adresse email, merci de cliquer sur le lien suivant pour confirmer votre email";


if (!$mail->send()) {

    echo "Le message n'a pas pu être envoyé.";
    echo "Mailer Erreur :" . $mail -> ErrorInfo;
}

else {
    echo "Le message a bien été envoyé";
}

?>