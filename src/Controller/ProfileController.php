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
                "id" => $post->getId(),
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

    public function AddComment($postId, $comment)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($this->CommentManager->addComment($comment, $postId, $user->getId())) {
            $success = "Commentaire ajouté";
            $session->set("success_message", $success);
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/profile");
    }
}