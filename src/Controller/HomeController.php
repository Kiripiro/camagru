<?php
class HomeController extends BaseController
{
    public function Home()
    {
        $session = new Session();
        $user = $session->get("user");
        $this->addParam('title', 'Home');
        $this->addParam('description', 'Home page');
        $this->addParam('user', $user);
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("home");
    }
}