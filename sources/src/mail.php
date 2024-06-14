<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
	$mail->isSMTP();
	$mail->Host = $_ENV['MAIL_HOST'] ?? '';
	$mail->SMTPAuth = true;
	$mail->Username = $_ENV['MAIL_USERNAME'] ?? '';
	$mail->Password = $_ENV['MAIL_PASSWORD'] ?? '';
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = intval($_ENV['MAIL_PORT'] ?? '') ?: 0;
	$mail->SMTPSecure = 'ssl';
	$mail->setFrom($_ENV['MAIL_USERNAME'] ?? '', $_ENV['MAIL_AUTHOR'] ?? '');
	$mail->isHTML(true);
} catch (Exception $e) {
	echo "Le message n'a pas pu être envoyé. Erreur de PHPMailer : {$mail->ErrorInfo}";
}

function sendMail($to, $subject, $body)
{
	echo "Envoi d'un email à $to avec le sujet $subject et le corps $body";
	global $mail;
	try {
		$mail->addAddress($to);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->send();
	} catch (Exception $e) {
		echo "Le message n'a pas pu être envoyé. Erreur de PHPMailer : {$mail->ErrorInfo}";
	}
}
