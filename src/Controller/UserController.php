<?php
class UserController extends BaseController
{
    public function Login()
    {
        $this->view("login");
    }

    public function Register()
    {
        $this->view("register");
    }

    public function RegisterNewUser($firstname, $lastname, $login, $email, $password, $confirmPassword)
    {
        if ($password == $confirmPassword) {
            $user = array(
                "firstname" => $firstname,
                "lastname" => $lastname,
                "login" => $login,
                "email" => $email,
                "password" => $password
            );
            $this->UserManager->addUser($user);
            header("Location: /login");
            $this->Login();
        } else {
            throw new PasswordNotMatchException();
        }
    }


    public function Authenticate($login, $password)
    {
        $user = $this->UserManager->getByEmail($login);
        var_dump($user);
    }
}