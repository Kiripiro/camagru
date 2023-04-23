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
        $this->addParam('session', $session);
        $allUsersPosts = $this->StudioManager->getAllUsersPosts($user->getId());
        $posts = array();
        foreach ($allUsersPosts as $post) {
            $posts[] = array(
                "path" => $post->getPath(),
                "description" => $post->getDescription(),
                "likes" => $post->getLikes()
            );
        }
        $this->addParam("posts", $posts);
        $this->addParam("nb_posts", count($posts));
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("profile");
    }
}