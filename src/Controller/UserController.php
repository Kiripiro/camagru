<?php
class UserController extends BaseController
{
    public function Login()
    {
        $this->view("login");
    }

    public function Authenticate($login, $password)
    {
        $user = $this->UserManager->getByEmail($login);
        var_dump($user);
    }
}