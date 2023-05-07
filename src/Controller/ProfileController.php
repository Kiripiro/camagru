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
            $comments = $this->CommentsManager->getPostComments($post->getId());
            foreach ($comments as $comment) {
                $comment->setUserLogin($this->UserManager->getById($comment->getUser_id())->getLogin());
            }
            $likes = $this->LikesManager->getPostLikes($post->getId());
            $posts[] = array(
                "id" => $post->getId(),
                "path" => $post->getPath(),
                "description" => $post->getDescription(),
                "likes" => count($likes),
                "comments" => $comments
            );
        }
        $this->addParam("posts", $posts);
        $this->addParam("nb_posts", count($posts));
        $this->addParam("success_message", $session->get('success_message'));
        $this->addParam("error_message", $session->get('error_message'));
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("profile");
    }

    public function AddComment($postId, $comment)
    {
        $session = new Session();
        $user = $session->get("user");
        if (empty($comment)) {
            $error = "Veuillez entrer un commentaire.";
            $session->set('error_message', $error);
            $this->redirect("/profile");
        }
        if (!$user) {
            throw new UserNotFoundException();
        }
        $post = $this->StudioManager->postExistsById($postId);
        if ($post == false) {
            throw new PostNotFoundException();
        }
        $userIdPost = $post->getUserId();
        $userPost = $this->UserManager->getById($userIdPost);
        if ($this->CommentsManager->addComment($comment, $postId, $user->getId())) {
            $success = "Commentaire ajouté";
            $session->set("success_message", $success);
            if ($userPost->getNotifs() == 1) {
                $mail = new Mail();
                if (!$mail->sendNewCommentMail($userPost->getFirstname(), $user->getLastname(), $userPost->getEmail())) {
                    $error = "Une erreur est survenue. Veuillez réessayer.";
                    $session->set('error_message', $error);
                }
            }
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/profile");
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
        $this->redirect("/profile");
    }

    public function ProfileDeletePost($pictureID)
    {
        if (empty($pictureID)) {
            $this->redirect("/profile");
        }
        $session = new Session();
        $user = $session->get('user');
        if ($user == null) {
            $this->redirect("/login");
        }
        $post = $this->StudioManager->getUsersPost($user->getId(), $pictureID);
        if ($post) {
            $this->CommentsManager->deleteComments($post->getId());
            $this->LikesManager->deleteLikes($post->getId());
            $this->StudioManager->deletePost($post->getId());
            $session->removeFromArray('posts', $post->getPath());
            unlink("Media/posts/" . $post->getPath() . ".png");
            $success = "Post supprimé";
            $session->set("success_message", $success);
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $session->set('error_message', $error);
        }
        $this->redirect("/profile");
    }
}