<?php
class UserController extends BaseController
{
    public function Login()
    {
        $session = new Session();
        date_default_timezone_set("Europe/Paris");
        if ($session->get("user") && $session->get("token_exp") > date("Y-m-d H:i:s")) {
            $this->redirect("/");
        }
        $this->addParam('title', 'Se connecter');
        $this->addParam('description', 'Se connecter à votre compte sur notre site');
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
        $this->addParam('title', 'Créer un compte');
        $this->addParam('description', 'Créer un compte sur notre site');
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
            throw new EmptyFieldsException(implode(', ', array_keys($emptyFields)));
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
        $user['password'] = password_hash($password, PASSWORD_BCRYPT);
        $this->UserManager->addUser($user);
        $mail = new Mail();
        $status = $mail->sendVerificationMail($firstname, $lastname, $email, $verificationToken);
        if ($status) {
            $success = "Nous vous avons envoyé un email de vérification. Merci de suivre les instructions pour activer votre compte.";
        } else {
            $failed = "Nous n'avons pas pu vous envoyer l'email de vérification. Merci d'utiliser un autre email.";
        }
        $this->addParam('message', $status ? $success : $failed);
        $this->view("register");
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
        $this->redirect("/");
    }

    public function ForgotPasswordView()
    {
        $this->addParam('title', 'Mot de passe oublié');
        $this->addParam('description', 'Mot de passe oublié ?');
        $this->view("forgotPassword");
    }

    public function ForgotPassword($email)
    {
        if (isset($email)) {
            $user = $this->UserManager->getBy("email", $email);
            if (!$user) {
                throw new UserNotFoundException();
            }
            $mail = new Mail();
            $status = $mail->sendResetPasswordMail($user->getFirstname(), $user->getLastname(), $user->getEmail(), $user->getVerificationToken());
            if ($status) {
                $success = "Nous vous avons envoyé un email. Merci de suivre les instructions pour changer votre mot de passe.";
            } else {
                $failed = "Nous n'avons pas pu vous envoyer l'email. Merci d'utiliser un autre email.";
            }
            $this->addParam('message', $status ? $success : $failed);
            $this->view("forgotPassword");
        }
    }

    public function resetPasswordView($token)
    {
        $session = new Session();
        $session->set("token", $token);
        $this->addParam('title', 'Changer de mot de passe');
        $this->addParam('description', 'Changer de mot de passe sur note site');
        $this->view("resetPassword");
    }

    public function resetPassword($oldPassword, $password, $confirmPassword)
    {
        $session = new Session();
        $token = $session->get("token");
        $user = $this->UserManager->getBy("verificationToken", $token);
        if (!$user) {
            throw new UserNotFoundException();
        }
        if (!password_verify($oldPassword, $user->getPassword())) {
            throw new WrongPasswordException();
        }
        PasswordValidator::validate($password, $confirmPassword);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->UserManager->setPassword($user->getId(), $user->getPassword());
        $session->destroy();
        $this->redirect("login");
    }
    public function SettingsView()
    {
        $session = new Session();
        $user = $session->get("user");
        $this->addParam('title', 'Paramètres');
        $this->addParam('description', 'Paramètres de votre compte');
        $this->addParam('user', $user);
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("settings");
    }
    public function updatePassword($oldPassword, $newPassword, $confirmPassword)
    {
        try {
            $session = new Session();
            $user = $session->get("user");
            if (!password_verify($oldPassword, $user->getPassword())) {
                throw new WrongPasswordException();
            }
            PasswordValidator::validate($newPassword, $confirmPassword);
            $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
            $this->UserManager->setPassword($user->getId(), $user->getPassword());
            $success = "Votre mot de passe a bien été modifié.";
        } catch (Exception $e) {
            $failed = $e->getMessage();
        }
        $this->addParam('message', isset($success) ? $success : $failed);
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->addParam('title', 'Paramètres');
        $this->addParam('description', 'Paramètres de votre compte');
        $this->view("settings");
    }
}