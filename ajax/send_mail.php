<?php
require '../lib/PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

// echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->setLanguage('ru');
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.yandex.ru';						  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'sc-online51@yandex.ru';            // SMTP username
$mail->Password = 'kyE4OzjgM';                           // SMTP password
// $mail->SMTPAutoTLS = false;
// $mail->SMTPOptions = array(
//     'ssl' => array(
//         'verify_peer' => false,
//         'verify_peer_name' => false,
//         'allow_self_signed' => false
//     )
// );
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$mail->setFrom('sc-online51@yandex.ru', 'Mailer');
$mail->addAddress('	blor+wfneejxxvyjqu6opi2w7@boards.trello.com', 'Trello BlOrTeam');     // Add a recipient
// $mail->addAddress('ellen@example.com');               // Name is optional
// $mail->addReplyTo('info@example.com', 'Information');
// $mail->addCC('cc@example.com');
// $mail->addBCC('bcc@example.com');

// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
// $mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Техподдерка БД ОнЛайн';
$mail->Body    = $_POST['msg'];
// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
	echo 'Message could not be sent.';
	echo 'Mailer Error: ' . $mail->ErrorInfo;
	// echo 'SSL Error: ' . openssl_error_string ();
} else {
	echo 'Message has been sent';
}

?>
