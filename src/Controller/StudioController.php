<?php
class StudioController extends BaseController
{
    public function StudioView()
    {
        $session = new Session();
        $user = $session->get('user');
        if ($user == null) {
            $this->redirect("/login");
        }
        $this->addParam("user", $user);
        $this->addParam("title", "Studio");
        $this->addParam("description", "Ajouter une image");
        $this->addParam("navbar", "View/Navbar/navbar.php");
        $this->view("studio");
    }
    public function addPicture()
    {
        $picture = new Picture();
    }
}