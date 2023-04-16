<?php
class User
{
    private $id;
    private $firstname;
    private $lastname;
    private $login;
    private $email;
    private $password;
    private $biography;
    private $avatar;
    private $notifs;
    private $admin;
    private $verificationToken;
    private $active;

    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }
    public function getFirstname()
    {
        return $this->firstname;
    }
    public function getLastname()
    {
        return $this->lastname;
    }
    public function getLogin()
    {
        return $this->login;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getBiography()
    {
        return $this->biography;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }
    public function getNotifs()
    {
        return $this->notifs;
    }
    public function getAdmin()
    {
        return $this->admin;
    }
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setBiography($biography)
    {
        $this->biography = $biography;
    }
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
    public function setNotifs($notifs)
    {
        $this->notifs = $notifs;
    }
    public function setVerificationToken($verificationToken)
    {
        $this->verificationToken = $verificationToken;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }
}