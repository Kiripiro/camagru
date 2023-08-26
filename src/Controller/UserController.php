<?php
class UserController extends BaseController
{
    public function Login()
    {
        $session = new Session();
        if ($session->get("user") && $session->get("token_exp") > date("Y-m-d H:i:s")) {
            $this->redirect("/");
        }
        if ($session->get("error_message")) {
            $this->addParam("error_message", $session->get("error_message"));
            $this->addParam('session', $session);
            $session->remove("error_message");
        }
        $this->addParam('title', 'Sign in');
        $this->addParam('description', 'Sign in to our website');
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
        $this->addParam('title', 'Create an account');
        $this->addParam('description', 'Create an account to our website');
        $session = new Session();
        if ($session->get("error_message")) {
            $this->addParam("error_message", $session->get("error_message"));
            $this->addParam('session', $session);
            $session->remove("error_message");
        }
        $this->view("register");
    }

    public function RegisterNewUser($firstname, $lastname, $username, $email, $password, $confirmPassword)
    {
        $user = array(
            "firstname" => htmlspecialchars($firstname),
            "lastname" => htmlspecialchars($lastname),
            "username" => htmlspecialchars($username),
            "email" => htmlspecialchars($email),
            "password" => htmlspecialchars($password),
            "verificationToken" => $verificationToken = bin2hex(random_bytes(16))
        );
        $session = new Session();
        $emptyFields = array_filter($user, function ($field) {
            return empty($field);
        });
        if (!empty($emptyFields)) {
            $sesion->set("error_message", "Please fill in all the fields.");
            $sesion->set("error_page", "/register");
            throw new EmptyFieldsException(implode(', ', array_keys($emptyFields)));
        }
        if ($this->UserManager->getBy($key = "username", $username)) {
            $session->set("error_message", "Username already exists.");
            $session->set("error_page", "/register");
            throw new UserAlreadyExistsException($key, $username);
        } else if ($this->UserManager->getBy($key = "email", $email)) {
            $session->set("error_message", "Email already in use.");
            $session->set("error_page", "/register");
            throw new UserAlreadyExistsException($key, $email);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $session->set("error_message", "Please use a valid email.");
            $session->set("error_page", "/register");
            throw new InvalidEmailException();
        }
        $passwordValidator = PasswordValidator::validate($password, $confirmPassword);
        if ($passwordValidator !== true) {
            $session->set("error_message", $passwordValidator);
            $session->set("error_page", "/register");
            throw new Exception($passwordValidator);
        }
        $user['password'] = password_hash($password, PASSWORD_BCRYPT);
        $this->UserManager->addUser($user);
        $mail = new Mail();
        $status = $mail->sendVerificationMail($firstname, $lastname, $email, $verificationToken);
        if ($status) {
            $success = "We sent you a verification email. Please follow the instructions to validate your account.";
        } else {
            $failed = "Sorry, we couldn't send you the verification email. Please make sure to use a valid email.";
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

    public function authenticate($username, $password)
    {
        if (empty($username) || empty($password)) {
            throw new EmptyFieldsException("username, password");
        }
        $session = new Session();
        $user = $this->UserManager->getBy("username", $username);
        if (!$user) {
            $session->set("error_message", "User not found.");
            $session->set("error_page", "/login");
            throw new UserNotFoundException();
        } else if (!password_verify($password, $user->getPassword())) {
            $session->set("error_message", "Wrong password. Please try again.");
            $session->set("error_page", "/login");
            throw new WrongPasswordException();
        } else if (!$user->getActive()) {
            $session->set("error_message", "User not verified.");
            $session->set("error_page", "/login");
            throw new UserNotVerifiedException($user->getFirstname(), $user->getLastname());
        }
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
        $userData = $this->UserManager->getUserSession($username);
        $session->set("user", $userData);
        $this->redirect("/");
    }

    public function ForgotPasswordView()
    {
        $this->addParam('title', 'Forgot password');
        $this->addParam('description', 'Forgot password ?');
        $session = new Session();
        if ($session->get("error_message")) {
            $this->addParam("error_message", $session->get("error_message"));
            $this->addParam('session', $session);
            $session->remove("error_message");
        }
        $this->view("forgotPassword");
    }

    public function ForgotPassword($email)
    {
        if (isset($email)) {
            $user = $this->UserManager->getBy("email", $email);
            if (!$user) {
                $session = new Session();
                $session->set("error_message", "User not found.");
                $session->set("error_page", "/forgot-password");
                throw new UserNotFoundException();
            }
            $mail = new Mail();
            $status = $mail->sendResetPasswordMail($user->getFirstname(), $user->getLastname(), $user->getEmail(), $user->getVerificationToken());
            if ($status) {
                $success = "We sent you an email. Please follow the instructions to update your password.";
            } else {
                $failed = "Sorry, we couldn't send you the email. Please use another email.";
            }
            $this->addParam('message', $status ? $success : $failed);
            $this->view("forgotPassword");
        }
    }

    public function resetPasswordView($token)
    {
        $session = new Session();
        if (!$token) {
            $session->set("error_message", "Missing token.");
            $session->set("error_page", "/reset-password");
            throw new InvalidTokenException();
        }
        if (!$this->UserManager->getBy("verificationToken", $token['token'])) {

            $session->set("error_message", "Invalid token.");
            $session->set("error_page", "/forgot-password");
            throw new InvalidTokenException();
        }
        $session->set("token", $token);
        $this->addParam('title', 'Update password');
        $this->addParam('description', 'Update your password');
        $session = new Session();
        if ($session->get("error_message")) {
            $this->addParam("error_message", $session->get("error_message"));
            $this->addParam('session', $session);
            $session->remove("error_message");
        }
        $this->view("resetPassword");
    }

    public function resetPassword($password, $confirmPassword)
    {
        $session = new Session();
        $token = $session->get("token");
        if (!$token) {
            $session->set("error_message", "Invalid token.");
            $session->set("error_page", "/reset-password");
            throw new InvalidTokenException();
        }
        $user = $this->UserManager->getBy("verificationToken", $token['token']);
        if (!$user) {
            $session->set("error_message", "User not found.");
            $session->set("error_page", "/reset-password");
            throw new UserNotFoundException();
        }
        $passwordValidator = PasswordValidator::validate($password, $confirmPassword);
        if ($passwordValidator !== true) {
            $session->set("error_message", $passwordValidator);
            $session->set("error_page", "/reset-password");
            $session->set("error_param", "?token=" . $token['token']);
            throw new Exception($passwordValidator);
        }
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
                $session = new Session();
                $session->set("error_message", "Wrong password. Please try again.");
                $session->set("error_page", "/settings");
                throw new WrongPasswordException();
            }
            $passwordValidator = PasswordValidator::validate($newPassword, $confirmPassword);
            if ($passwordValidator !== true) {
                $session->set("error_message", $passwordValidator);
                $session->set("error_page", "/settings");
                throw new Exception($passwordValidator);
            }
            $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
            $this->UserManager->setPassword($user->getId(), $user->getPassword());
            $success = "Your password has been updated.";
        } catch (Exception $e) {
            $failed = $e->getMessage();
        }
        $this->addParam('message', isset($success) ? $success : $failed);
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->addParam('title', 'Settings');
        $this->addParam('description', 'Your account settings');
        $this->view("settings");
    }

    public function SettingsView()
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            $this->redirect('/login');
        }
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
        }
        $success_message = $session->get("success_message");
        $error_message = $session->get("error_message");
        $this->addParam('title', 'Settings');
        $this->addParam('description', 'Your account settings');
        $this->addParam('session', $session);
        $this->addParam('user', $user);
        $this->addParam('success_message', $success_message);
        $this->addParam('error_message', $error_message);
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("settings");
    }

    public function SettingsUsername($username)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
        }
        $user = $this->UserManager->getById($user->getId());
        if (!$user) {
            $session->set("error_message", "User not found.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        if ($user->getUsername() === $username) {
            $session->set("error_message", "Your username is already up to date.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        $userExists = $this->UserManager->getByUsername($username);
        if ($userExists) {
            $session->set("error_message", "Username already exists.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        $username = htmlspecialchars($username);
        if ($this->UserManager->updateUsername($user, $username)) {
            $user->setUsername($username);
            $session->set("user", $user);
            $success = "Your username has been updated.";
            $session->set("success_message", $success);
        } else {
            $failed = "An error has occured. Please try again.";
            $session->set("error_message", $failed);
        }
        $this->redirect("/settings");
    }

    public function SettingsEmail($email)
    {
        $session = new Session();

        if (!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $session->set("error_message", "Please enter a valid email.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        $user = $session->get("user");
        if (!$user) {
            $this->redirect('/login');
        }
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
        }
        $user = $this->UserManager->getById($user->getId());
        if (!$user) {
            throw new UserNotFoundException();
        }
        $userExists = $this->UserManager->getByEmail($email);
        if ($userExists) {
            $session->set("error_message", "Email already in use.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        $email = htmlspecialchars($email);
        if ($this->UserManager->updateEmail($user, $email)) {
            $user->setEmail($email);
            $session->set("user", $user);
            $success = "Your email has been updated.";
            $session->set('success_message', $success);
        } else {
            $error = "An error has occured. Please try again.";
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
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
        }
        $actualBio = $user->getBiography();
        if ($actualBio === $biography) {
            $session->set("error_message", "Your biography is already up to date.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        if (strlen($biography) > 255) {
            $session->set("error_message", "Your biography is too long.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        $biography = htmlspecialchars($biography);
        if ($this->UserManager->updateBiography($user, $biography)) {
            $user->setBiography($biography);
            $session->set("user", $user);
            $success = "Your biography has been updated.";
            $session->set('success_message', $success);
        } else {
            $error = "An error has occured. Please try again.";
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
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
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
                $error = "An error has occured. Please try again.";
                $session->set('error_message', $error);
                return;
            }
        }
        if ($this->UserManager->delete($user)) {
            $session->destroy();
        } else {
            $error = "An error has occured. Please try again.";
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
            if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
                $this->redirect("/login");
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
            $tmp = $_FILES['upload']['tmp_name'];
            $mime_type = mime_content_type($tmp);
            if (!in_array($mime_type, array_keys($ALLOWED_FILES))) {
                $session->set("error_message", "Invalid file type.");
                $session->set("error_page", "/settings");
                $this->redirect("/settings");
            }
            $extension = $ALLOWED_FILES[$mime_type];
            $uploaded_file = uniqid() . '.' . $extension;
            $filepath = $UPLOAD_DIR . '/' . $uploaded_file;
            $success = move_uploaded_file($tmp, $filepath);
            if ($success) {
                $imageResizer = new ImageResizer($filepath);
                $imageResizer->resizeImage(128, 128, 'crop');
                $imageResizer->saveImage($filepath, 100);
            } else {
                throw new FileUploadException();
            }
            if ($this->UserManager->updateAvatar($user, $uploaded_file)) {
                $user->setAvatar($uploaded_file);
                $session->set("user", $user);
                $success = "Your profile picture has been updated.";
                $session->set('success_message', $success);
            } else {
                $error = "An error has occured. Please try again.";
                $session->set('error_message', $error);
            }
        } else {
            $error = "Please select an image and try again.";
            $session->set('error_message', $error);
        }
        $this->redirect("/settings");
    }


    public function SettingsUpdatePassword($password, $newPassword, $confirmPassword)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            $this->redirect('/login');
        }
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
        }
        $userExists = $this->UserManager->getById($user->getId());
        if (!$userExists) {
            $session->set("error_message", "User not found.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        if (!password_verify($password, $userExists->getPassword())) {
            $session->set("error_message", "Wrong password. Please try again.");
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        $passwordValidator = PasswordValidator::validate($newPassword, $confirmPassword);
        if ($passwordValidator !== true) {
            $session->set("error_message", $passwordValidator);
            $session->set("error_page", "/settings");
            $this->redirect("/settings");
        }
        if ($this->UserManager->updatePassword($userExists, $newPassword)) {
            $success = "Your password has been updated.";
            $session->set('success_message', $success);
        } else {
            $error = "An error has occured. Please try again.";
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
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
        }
        $userExists = $this->UserManager->getById($user->getId());
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        if ($this->UserManager->updateNotifs($user, $value)) {
            $success = "Your email notifications have been " . $value . ".";
            $session->set('success_message', $success);
        } else {
            $error = "An error has occured. Please try again.";
            $session->set('error_message', $error);
        }
        $this->redirect("/settings");
    }

    public function SearchUser($token, $username)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            http_response_code(401);
            header('Content-Type: application/json');
            $response = array(
                "success" => false,
                "error" => "Your session has expired. Please sign in again."
            );
            echo json_encode($response);
            exit;
        }
        $userExists = $this->UserManager->getById($user->getId());
        if (!$userExists) {
            throw new UserNotFoundException();
        }
        if ($token == $userExists->getToken()) {
            $user = $this->UserManager->getByUsername($username);
            if (!$user) {
                $response = array(
                    "success" => false,
                    "error" => "User doesn't exist."
                );

                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            if ($user->getUsername() === $userExists->getUsername()) {
                $response = array(
                    "success" => true,
                    "isProfile" => true
                );

                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            $response = array(
                "success" => true,
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            $response = array(
                "success" => false,
                "error" => "You are not authorized to perform this action."
            );

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
}
