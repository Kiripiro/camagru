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
        if (empty($token)) {
            throw new EmptyFieldsException("token");
        }
        $user = $this->UserManager->getBy("verificationToken", $token["token"]);
        if (!$user) {
            throw new UserNotFoundException();
        } else if ($user->getActive()) {
            throw new UserAlreadyVerifiedException($user->getFirstname(), $user->getLastname());
        }
        $user->setActive(true);
        $this->UserManager->setActive($user->getId());
        $this->redirect("login");
    }

    public function authenticate($login, $password)
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
        $session = new Session();
        if (!$user->getToken() || $user->getTokenExp() < date('Y-m-d H:i:s')) {
            $token = bin2hex(random_bytes(16));
            $exp = new DateTime("+1 day", new DateTimeZone("Europe/Paris"));
            $exp->add(new DateInterval("PT2H"));
            $token_exp = $exp->format("Y-m-d H:i:s");
            setcookie('token', $token, $exp->getTimestamp(), "/", "", false, false);
            $this->UserManager->setToken($user->getId(), $token, $token_exp);
        } else {
            $exp = new DateTime($user->getTokenExp(), new DateTimeZone("Europe/Paris"));
            setcookie('token', $user->getToken(), $exp->getTimestamp(), "/", "", false, false);
        }
        $userData = $this->UserManager->getUserSession($login);
        $session->set("user", $userData);
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

    public function resetPassword($password, $confirmPassword)
    {
        $session = new Session();
        $token = $session->get("token");
        $user = $this->UserManager->getBy("verificationToken", $token['token']);
        if (!$user) {
            throw new UserNotFoundException();
        }
        PasswordValidator::validate($password, $confirmPassword);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->UserManager->setPassword($user->getId(), $user->getPassword());
        $session->destroy();
        $this->redirect("login");
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

    public function SettingsView()
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $success_message = $session->get("success_message");
        $error_message = $session->get("error_message");
        $this->addParam('title', 'Paramètres');
        $this->addParam('description', 'Paramètres de votre compte');
        $this->addParam('session', $session);
        $this->addParam('user', $user);
        $this->addParam('success_message', $success_message);
        $this->addParam('error_message', $error_message);
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("settings");
    }

    public function SettingsLogin($login)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $user = $this->UserManager->getById($user->getId());
        if (!$user) {
            throw new UserNotFoundException();
        }
        $userExists = $this->UserManager->getByLogin($login);
        if ($userExists) {
            throw new UserAlreadyExistsException('login', $login);
        }
        if ($this->UserManager->updateLogin($user, $login)) {
            $user->setLogin($login);
            $session->set("user", $user);
            $success = "Votre login a bien été modifié.";
            $session->set("success_message", $success);
        } else {
            $failed = "Une erreur est survenue. Veuillez réessayer.";
            $session->set("error_message", $failed);
        }
        $this->redirect("/settings");
    }

    public function SettingsEmail($email)
    {

        if (!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $user = $this->UserManager->getById($user->getId());
        if (!$user) {
            throw new UserNotFoundException();
        }
        $userExists = $this->UserManager->getByEmail($email);
        if ($userExists) {
            throw new UserAlreadyExistsException('email', $email);
        }
        if ($this->UserManager->updateEmail($user, $email)) {
            $user->setEmail($email);
            $session->set("user", $user);
            $success = "Votre email a bien été modifié.";
            $session->set('success_message', $success);
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/settings");
    }

    public function SettingsBiography($biography)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $userExists = $this->UserManager->getById($user->getId()); // Vraiment utile ?
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        if ($this->UserManager->updateBiography($user, $biography)) {
            $user->setBiography($biography);
            $session->set("user", $user);
            $success = "Votre biographie a bien été modifiée.";
            $session->set('success_message', $success);
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/settings");
    }

    public function SettingsDelete($password)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $user = $this->UserManager->getById($user->getId());
        if (!$user) {
            throw new UserNotFoundException();
        }
        if (!password_verify($password, $user->getPassword())) {
            throw new WrongPasswordException();
        }
        $posts = $this->StudioManager->getAllUsersPosts($user->getId());
        foreach ($posts as $post) {
            if (file_exists("Media/posts/" . $post->getPath() . ".png"))
                unlink("Media/posts/" . $post->getPath() . ".png");
            else if (file_exists("Media/posts/" . $post->getPath() . ".jpg"))
                unlink("Media/posts/" . $post->getPath() . ".jpg");
            else if (file_exists("Media/posts/" . $post->getPath() . ".jpeg"))
                unlink("Media/posts/" . $post->getPath() . ".jpeg");
            else {
                $error = "Une erreur est survenue. Veuillez réessayer.";
                $session->set('error_message', $error);
                return;
            }
        }
        if ($this->UserManager->delete($user)) {
            $session->destroy();
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/");
    }

    public function SettingsUpdateAvatar()
    {
        $session = new Session();
        if (!empty($_FILES['upload']['name'])) {
            $fileSize = $_FILES['upload']['size'];
            if ($fileSize > 2097152) {
                throw new FileUploadException();
            }
            $user = $session->get("user");
            if (!$user) {
                throw new UserNotFoundException();
            }
            $userExists = $this->UserManager->getById($user->getId());
            if (!$userExists) {
                throw new UserNotFoundException();
            }
            $UPLOAD_DIR = __DIR__ . "/../Media/avatars/";
            $ALLOWED_FILES = [
                'image/png' => 'png',
                'image/jpeg' => 'jpg'
            ];
            $filename = $_FILES["upload"]["name"];
            $tmp = $_FILES['upload']['tmp_name'];
            $mime_type = mime_content_type($tmp);
            if (!in_array($mime_type, array_keys($ALLOWED_FILES))) {
                throw new InvalidFileException($mime_type);
            }
            $uploaded_file = pathinfo($filename, PATHINFO_FILENAME) . '.' . $ALLOWED_FILES[$mime_type];
            $filepath = $UPLOAD_DIR . '/' . $uploaded_file;
            $success = move_uploaded_file($tmp, $filepath);
            if ($success) {
                $resizeObj = new imageResizer($filepath);
                $resizeObj->resizeImage(128, 128, 'crop');
                $resizeObj->saveImage($filepath, 100);
            } else {
                throw new FileUploadException();
            }
            if ($this->UserManager->updateAvatar($user, $uploaded_file)) {
                $user->setAvatar($uploaded_file);
                $session->set("user", $user);
                $success = "Votre avatar a bien été modifié.";
                $session->set('success_message', $success);
            } else {
                $error = "Une erreur est survenue. Veuillez réessayer.";
                $session->set('error_message', $error);
            }
        } else {
            $error = "Merci de choisir une photo de profil. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/settings");
    }

    public function SettingsUpdatePassword($password, $newPassword, $confirmPassword)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $userExists = $this->UserManager->getById($user->getId());
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        if (!password_verify($password, $userExists->getPassword())) {
            throw new WrongPasswordException();
        }
        PasswordValidator::validate($newPassword, $confirmPassword);
        if ($this->UserManager->updatePassword($userExists, $newPassword)) {
            $success = "Votre mot de passe a bien été modifiée.";
            $session->set('success_message', $success);
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/settings");
    }

    public function SettingsUpdateNotifications($value)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $userExists = $this->UserManager->getById($user->getId());
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        if ($this->UserManager->updateNotifs($user, $value)) {
            if ($value == "activated")
                $value = "activées";
            else if ($value == "deactivated")
                $value = "désactivées";
            $success = "Les notifications par email ont bien été " . $value . ".";
            $session->set('success_message', $success);
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/settings");
    }

    public function SearchUser($token, $userLogin)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $userExists = $this->UserManager->getById($user->getId());
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        if ($token == $userExists->getToken()) {
            $user = $this->UserManager->getByLogin($userLogin);
            if (!$user) {
                $response = array(
                    "success" => false,
                    "error" => "L'utilisateur n'existe pas."
                );

                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }
            if ($user->getLogin() === $userExists->getLogin()) {
                $response = array(
                    "success" => true,
                    "isProfile" => true
                );

                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }
            $response = array(
                "success" => true,
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        } else {
            $response = array(
                "success" => false,
                "error" => "Vous n'êtes pas autorisé à effectuer cette action."
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
    }
}