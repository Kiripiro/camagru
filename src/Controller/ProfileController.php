<?php
class ProfileController extends BaseController
{
    public function ProfileView()
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        $this->addParam('user', $user);
        $this->addParam("title", "Profile");
        $this->addParam("description", "Profile");
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("profile");
    }
}