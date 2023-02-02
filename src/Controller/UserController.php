<?php
class UserController extends BaseController
{
    public function Login()
    {
        $session = new Session();
        date_default_timezone_set("Europe/Paris");
        if ($session->get("user") && $session->get("token_exp") > date("Y-m-d H:i:s")) {
            $this->redirect("home");
        }
        $this->addParam('title', 'Login');
        $this->addParam('description', 'Login on our website');
        $this->view("login");
    }

    public function Logout()
    {
        $session = new Session();
        $session->destroy();
        $this->redirect("login");
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
            "password" => $password,
            "verificationToken" => $verificationToken = bin2hex(random_bytes(16))
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
        $mail = new Mail();
        if ($mail->sendVerificationMail($firstname, $lastname, $email, $verificationToken))
            echo "We sent you an email to verify your account.";
    }

    public function Verify($token)
    {
        $user = $this->UserManager->getBy("verificationToken", $token);
        if (!$user) {
            throw new UserNotFoundException();
        } else if ($user->getActive()) {
            throw new UserAlreadyVerifiedException($user->getFirstname(), $user->getLastname());
        }
        $user->setActive(true);
        $this->UserManager->setActive($user->getId());
        $this->redirect("login");
    }

    public function Authenticate($login, $password)
    {
        if (empty($login) || empty($password)) {
            throw new EmptyFieldsException("login, password");
        }
        $user = $this->UserManager->getBy("login", $login);
        if (!$user) {
            throw new UserNotFoundException();
        } else if (!password_verify($password, $user->getPassword())) {
            throw new WrongPasswordException();
        } else if (!$user->getActive()) {
            throw new UserNotVerifiedException($user->getFirstname(), $user->getLastname());
        }
        $token = bin2hex(random_bytes(16));
        date_default_timezone_set("Europe/Paris");
        $token_exp = date("Y-m-d H:i:s", strtotime("+1 day"));
        $session = new Session();
        $session->set("user", $user);
        $session->set("token", $token);
        $session->set("token_exp", $token_exp);
        $this->UserManager->setToken($user->getId(), $token, $token_exp);
        $this->redirect("home");
    }
}