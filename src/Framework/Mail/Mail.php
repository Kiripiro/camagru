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
        $this->_mail->setFrom("noreply.camagru@gmail.com");
        $this->_mail->Body = "Hello $firstname $lastname,\nPlease click on the following link to verify your email address:\nhttps://camagru.fr/verify?token=$verificationToken";
        $this->_mail->addAddress($email);
        if (!$this->_mail->send()) {
            throw new MailHasNotBeenSentException();
        }
        $this->_mail->smtpClose();
        return true;
    }
}