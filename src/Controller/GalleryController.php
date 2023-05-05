<?php
class GalleryController extends BaseController
{
    public function GalleryView()
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
        $allPosts = $this->StudioManager->getAllPosts();
        $posts = array();
        foreach ($allPosts as $post) {
            $comments = $this->CommentsManager->getPostComments($post->getId());
            foreach ($comments as $comment) {
                $comment->setUserLogin($this->UserManager->getById($comment->getUser_id())->getLogin());
            }
            $likes = $this->LikesManager->getPostLikes($post->getId());
            $posts[] = array(
                "id" => $post->getId(),
                "path" => $post->getPath(),
                "description" => $post->getDescription(),
                "date" => $post->getDate(),
                "likes" => count($likes),
                "comments" => $comments,
                "user_avatar" => $this->UserManager->getById($post->getUserId())->getAvatar(),
                "user_login" => $this->UserManager->getById($post->getUserId())->getLogin(),
                "user_firstname" => $this->UserManager->getById($post->getUserId())->getFirstname(),
                "user_lastname" => $this->UserManager->getById($post->getUserId())->getLastname(),
            );
        }
        $this->addParam("posts", $posts);
        $this->addParam("success_message", $session->get('success_message'));
        $this->addParam("error_message", $session->get('error_message'));
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("gallery");
    }

    public function AddLike($postId)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($this->StudioManager->postExistsById($postId) == false) {
            throw new PostNotFoundException();
        }
        $return = $this->LikesManager->addLike($postId, $user->getId());
        if ($return == "Like removed") {
            $success = "Like retiré";
            $session->set("success_message", $success);
        } else if ($return == "Like added") {
            $success = "Like ajouté";
            $session->set("success_message", $success);
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/gallery");
    }
}