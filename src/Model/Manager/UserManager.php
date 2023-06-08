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
        $userObj->setUsername($user["username"]);
        $userObj->setEmail($user["email"]);
        $userObj->setPassword($user["password"]);
        $userObj->setVerificationToken($user["verificationToken"]);
        $this->create($userObj, ["firstname", "lastname", "username", "email", "password", "verificationToken"]);
    }

    public function getById($id)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE id=?");
        $req->execute(array($id));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
        return $req->fetch();
    }

    public function getByEmail($email)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE email=?");
        $req->execute(array($email));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
        return $req->fetch();
    }

    public function getByUsername($username)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE username=?");
        $req->execute(array($username));
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

    public function getUserSession($username)
    {
        $req = $this->_bdd->prepare("SELECT id, firstname, lastname, username, email, biography, avatar, notifs, admin, token, token_exp, verificationToken, active FROM users WHERE username=?");
        $req->execute(array($username));
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

    public function updateAvatar($user, $file)
    {
        try {
            $user->setAvatar($file);
            $this->update($user, ["avatar"]);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateUsername($user, $username)
    {
        try {
            $user->setUsername($username);
            $this->update($user, ["username"]);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateEmail($user, $email)
    {
        try {
            $user->setEmail($email);
            $this->update($user, ["email"]);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateBiography($user, $biography)
    {
        try {
            $user->setBiography($biography);
            $this->update($user, ["biography"]);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function updatePassword($user, $password)
    {
        try {
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $this->update($user, ["password"]);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateNotifs($user, $value)
    {
        try {
            if ($value == "activated") {
                $user->setNotifs(1);
                $this->update($user, ["notifs"]);
                return true;
            } else if ($value == "deactivated") {
                $user->setNotifs(0);
                $this->update($user, ["notifs"]);
                return true;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function verifyToken($userId, $token)
    {
        try {
            $req = $this->_bdd->prepare("SELECT * FROM users WHERE id=? AND token=? AND token_exp > NOW()");
            $req->execute(array($userId, $token));
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
            $user = $req->fetch();
            if (!$user)
                return false;
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}