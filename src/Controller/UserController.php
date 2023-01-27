<?php
class UserController extends BaseController
{
    public function Login()
    {
        $this->addParam('title', 'Login');
        $this->addParam('description', 'Login on our website');
        $this->view("login");
    }

    public function Register()
    {
        $this->addParam('title', 'Register');
        $this->addParam('description', 'Create a new account on our website');
        $this->view("register");
    }

    public function RegisterNewUser($firstname, $lastname, $login, $email, $password, $confirmPassword)
    {
        $user = array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "login" => $login,
            "email" => $email,
            "password" => $password
        );
        $emptyFields = array_filter($user, function ($field) {
            return empty($field);
        });
        if (!empty($emptyFields)) {
            throw new EmptyFieldsException(implode(',', array_keys($emptyFields)));
        }
        if ($this->UserManager->getBy($key = "login", $login)) {
            throw new UserAlreadyExistsException($key, $login);
        } else if ($this->UserManager->getBy($key = "email", $email)) {
            throw new UserAlreadyExistsException($key, $email);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
        PasswordValidator::validate($password, $confirmPassword);
        $user['password'] = (password_hash($password, PASSWORD_BCRYPT));
        $this->UserManager->addUser($user);
        $this->redirect('login');
    }

    public function Authenticate($login, $password)
    {
        $user = $this->UserManager->getByEmail($login);
        var_dump($user);
    }
}