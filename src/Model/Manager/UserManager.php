<?php
class userManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("users", "User", $datasource);
    }

    public function addUser($user)
    {
        $userObj = new User();
        $userObj->setFirstname($user["firstname"]);
        $userObj->setLastname($user["lastname"]);
        $userObj->setLogin($user["login"]);
        $userObj->setEmail($user["email"]);
        $userObj->setPassword($user["password"]);
        $userObj->setVerificationToken($user["verificationToken"]);
        $this->create($userObj, ["firstname", "lastname", "login", "email", "password", "verificationToken"]);
    }

    public function getByEmail($email)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE email=?");
        $req->execute(array($email));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
        return $req->fetch();
    }

    public function getByLogin($login)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE login=?");
        $req->execute(array($login));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
        return $req->fetch();
    }

    public function getBy($key, $value)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE $key=?");
        $req->execute(array($value));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
        return $req->fetch();
    }

    public function setActive($id)
    {
        $req = $this->_bdd->prepare("UPDATE users SET active = 1 WHERE id = ?");
        $req->execute(array($id));
    }

    public function setToken($id, $token, $token_exp)
    {
        $req = $this->_bdd->prepare("UPDATE users SET token = ?, token_exp = ? WHERE id = ?");
        $req->execute(array($token, $token_exp, $id));
    }

    public function setPassword($id, $password)
    {
        $req = $this->_bdd->prepare("UPDATE users SET password = ? WHERE id = ?");
        $req->execute(array($password, $id));
    }
}