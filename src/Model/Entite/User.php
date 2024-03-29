<?php
class User
{
    private $id;
    private $firstname;
    private $lastname;
    private $username;
    private $email;
    private $password;
    private $biography;
    private $avatar;
    private $notifs;
    private $admin;
    private $token;
    private $token_exp;
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
    public function getUsername()
    {
        return $this->username;
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
    public function getToken()
    {
        return $this->token;
    }
    public function getTokenExp()
    {
        return $this->token_exp;
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

    public function setUsername($username)
    {
        $this->username = $username;
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
    public function setToken($token)
    {
        $this->token = $token;
    }
    public function setTokenExp($token_exp)
    {
        $this->token_exp = $token_exp;
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