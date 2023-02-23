<?php

require_once("PHPMailer.php");
require_once("SMTP.php");
require_once("Exception.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $_mail;
    public function __construct()
    {
        $this->_mail = new PHPMailer();
        $this->_mail->isSMTP();
        $this->_mail->Host = 'smtp.gmail.com';
        $this->_mail->SMTPAuth = true;
        $this->_mail->SMTPSecure = "tls";
        $this->_mail->Port = 587;
        $this->_mail->Username = $_ENV["PHP_MAIL_USER"];
        $this->_mail->Password = $_ENV["PHP_MAIL_PASSWORD"];
    }

    public function sendVerificationMail($firstname, $lastname, $email, $verificationToken)
    {
        $this->_mail->Subject = 'Camagru - Email verification';
        $this->_mail->setFrom("atourret42.camagru@gmail.com");
        $this->_mail->Body = "Bonjour $firstname $lastname,\nMerci de cliquer sur le lien suivant pour activer votre compte:\nhttps://camagru.fr/verify?token=$verificationToken";
        $this->_mail->addAddress($email);
        if (!$this->_mail->send()) {
            throw new MailHasNotBeenSentException();
        }
        $this->_mail->smtpClose();
        return true;
    }

    public function sendResetPasswordMail($firstname, $lastname, $email, $verificationToken)
    {
        $this->_mail->Subject = 'Camagru - Récupération de mot de passe';
        $this->_mail->setFrom("atourret42.camagru@gmail.com");
        $this->_mail->Body = "Bonjour $firstname $lastname,\nMerci de cliquer sur le lien suivant pour réinitialiser votre mot de passe:\nhttps://camagru.fr/reset-password?token=$verificationToken";
        $this->_mail->addAddress($email);
        if (!$this->_mail->send()) {
            throw new MailHasNotBeenSentException();
        }
        $this->_mail->smtpClose();
        return true;
    }
}