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
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
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
                $comment->setUsername($this->UserManager->getById($comment->getUser_id())->getUsername());
            }
            $likes = $this->LikesManager->getPostLikes($post->getId());
            $posts[] = array(
                "id" => $post->getId(),
                "path" => $post->getPath(),
                "description" => $post->getDescription(),
                "likes" => count($likes),
                "liked" => $this->LikesManager->likeExists($post->getId(), $user->getId()),
                "comments" => $comments,
                "comments_count" => count($comments)
            );
        }
        $this->addParam("posts", $posts);
        $this->addParam("nb_posts", count($posts));
        $this->addParam("success_message", $session->get('success_message'));
        $this->addParam("error_message", $session->get('error_message'));
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("profile");
    }

    public function ProfileUserView($userId)
    {
        $session = new Session();
        $user = $session->get("user");
        if (!$user) {
            throw new UserNotFoundException();
        }
        if ($user->getTokenExp() <= date('Y-m-d H:i:s')) {
            $this->redirect("/login");
        }

        $this->addParam('user', $user);
        $this->addParam("title", "Profile");
        $this->addParam("description", "Profile");
        $this->addParam('session', $session);
        $userProfile = $this->UserManager->getBy("username", $userId["username"]);
        if (!$userProfile) {
            throw new UserNotFoundException();
        }
        $allUsersPosts = $this->StudioManager->getAllUsersPosts($userProfile->getId());
        $posts = array();
        foreach ($allUsersPosts as $post) {
            $comments = $this->CommentsManager->getPostComments($post->getId());
            foreach ($comments as $comment) {
                $comment->setUsername($this->UserManager->getById($comment->getUser_id())->getUsername());
            }
            $likes = $this->LikesManager->getPostLikes($post->getId());
            $posts[] = array(
                "id" => $post->getId(),
                "path" => $post->getPath(),
                "description" => $post->getDescription(),
                "likes" => count($likes),
                "liked" => $this->LikesManager->likeExists($post->getId(), $user->getId()),
                "comments" => $comments,
                "comments_count" => count($comments)
            );
        }
        $this->addParam("posts", $posts);
        $this->addParam("nb_posts", count($posts));
        $this->addParam("success_message", $session->get('success_message'));
        $this->addParam("error_message", $session->get('error_message'));
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->addParam('userProfile', $userProfile);
        $this->view("usersProfile");
    }
}