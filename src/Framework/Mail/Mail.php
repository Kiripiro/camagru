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
        $this->_mail->Body = "Bonjour $firstname $lastname,\nPlease click on the following link to activate your account:\nhttps://" . $_SERVER['HTTP_HOST'] . "/verify?token=$verificationToken";
        $this->_mail->addAddress($email);
        if (!$this->_mail->send()) {
            throw new MailHasNotBeenSentException();
        }
        $this->_mail->smtpClose();
        return true;
    }

    public function sendResetPasswordMail($firstname, $lastname, $email, $verificationToken)
    {
        $this->_mail->Subject = 'Camagru - Update password';
        $this->_mail->setFrom("atourret42.camagru@gmail.com");
        $this->_mail->Body = "Bonjour $firstname $lastname,\nPlease click on the following link to reset your password:\nhttps://" . $_SERVER['HTTP_HOST'] . "/reset-password?token=$verificationToken";
        $this->_mail->addAddress($email);
        if (!$this->_mail->send()) {
            throw new MailHasNotBeenSentException();
        }
        $this->_mail->smtpClose();
        return true;
    }

    public function sendNewCommentMail($firstname, $lastname, $email)
    {
        $this->_mail->Subject = 'Camagru - New comment';
        $this->_mail->setFrom("atourret42@gmail.com");
        $this->_mail->Body = "Bonjour $firstname $lastname,\nA new comment has been published on one of your posts.";
        $this->_mail->addAddress($email);
        if (!$this->_mail->send()) {
            throw new MailHasNotBeenSentException();
        }
        $this->_mail->smtpClose();
        return true;
    }
}
