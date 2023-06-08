<?php
class GalleryController extends BaseController
{
    public function GalleryView()
    {
        $session = new Session();
        $user = $session->get("user");
        $this->addParam('user', $user);
        $this->addParam("title", "Gallery");
        $this->addParam("description", "Gallery");
        $this->addParam('session', $session);
        $allPosts = $this->StudioManager->getNbPosts(0, 9);
        $posts = array();
        foreach ($allPosts as $post) {
            $comments = $this->CommentsManager->getPostComments($post->getId());
            foreach ($comments as $comment) {
                $comment->setUsername($this->UserManager->getById($comment->getUser_id())->getUsername());
            }
            $likes = $this->LikesManager->getPostLikes($post->getId());
            $postUser = $this->UserManager->getById($post->getUserId());
            $posts[] = array(
                "id" => $post->getId(),
                "path" => $post->getPath(),
                "description" => $post->getDescription(),
                "date" => $post->getDate(),
                "likes" => count($likes),
                "comments" => $comments,
                "comments_count" => count($comments),
                "liked" => ($user) ? $this->LikesManager->likeExists($post->getId(), $user->getId()) : null,
                "user_avatar" => $postUser->getAvatar(),
                "username" => $postUser->getUsername(),
                "user_firstname" => $postUser->getFirstname(),
                "user_lastname" => $postUser->getLastname(),
            );
        }
        $this->addParam("posts", $posts);
        $this->addParam("success_message", $session->get('success_message'));
        $this->addParam("error_message", $session->get('error_message'));
        $this->addParam('navbar', 'View/Navbar/navbar.php');
        $this->view("gallery");
    }

    public function AddLike($token, $postId)
    {
        $session = new Session();
        $user = $session->get("user");

        if ($user) {
            if (!$this->UserManager->verifyToken($user->getId(), $token)) {
                $response = array(
                    "success" => false,
                    "message" => "Token invalide"
                );
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "User not logged in"
            );
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        if (!$user) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(array("error" => "User not logged in"));
            exit;
        }
        if ($this->StudioManager->postExistsById($postId) == false) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(array("error" => "Post not found"));
            exit;
        }
        $return = $this->LikesManager->addLike($postId, $user->getId());
        if ($return === false) {
            $return = "Une erreur est survenue. Veuillez réessayer.";
            http_response_code(400);
            echo json_encode(array("error" => $return));
            exit;
        }

        $response = array(
            "success" => true,
            "message" => $return
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function AddComment($token, $postId, $comment)
    {
        $session = new Session();
        $user = $session->get("user");

        if ($user) {
            if (!$this->UserManager->verifyToken($user->getId(), $token)) {
                $response = array(
                    "success" => false,
                    "message" => "Token invalide"
                );
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "User not logged in"
            );
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        if (!$user) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(array("error" => "User not logged in"));
            exit;
        }
        if ($this->StudioManager->postExistsById($postId) == false) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(array("error" => "Post not found"));
            exit;
        }
        if (empty($comment)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array("error" => "Veuillez entrer un commentaire."));
            exit;
        }
        $return = $this->CommentsManager->addComment($comment, $postId, $user->getId());
        if ($return === false) {
            $return = "Une erreur est survenue. Veuillez réessayer.";
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array("error" => $return));
            exit;
        } else if ($return === "Commentaire ajouté") {
            $post = $this->StudioManager->getById($postId);
            $userPost = $this->UserManager->getById($post->getUserId());
            if ($userPost->getNotifs() == 1) {
                $mail = new Mail();
                if (!$mail->sendNewCommentMail($userPost->getFirstname(), $userPost->getLastname(), $userPost->getEmail())) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(array("error" => "Une erreur est survenue. Veuillez réessayer."));
                    exit;
                } else {
                    $response = array(
                        "success" => true,
                        "message" => $return,
                        "mail" => "Mail sent",
                        "user" => $userPost->getUsername(),
                        "comment" => $comment
                    );
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response = array(
                    "success" => true,
                    "message" => $return,
                    "user" => $userPost->getUsername(),
                    "comment" => $comment
                );
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        }
    }

    public function InfiniteScrollLoad($token, $offset)
    {
        $session = new Session();
        $user = $session->get("user");
        if ($user) {
            if (!$this->UserManager->verifyToken($user->getId(), $token)) {
                $response = array(
                    "success" => false,
                    "message" => "Token invalide"
                );
                return $response;
            }
        }

        $allPosts = $this->StudioManager->getNbPosts($offset, 9);
        $postsHTML = '';
        $i = $offset;
        foreach ($allPosts as $post) {
            $i++;
            $comments = $this->CommentsManager->getPostComments($post->getId());
            foreach ($comments as $comment) {
                $comment->setUsername($this->UserManager->getById($comment->getUser_id())->getUsername());
            }
            $likes = $this->LikesManager->getPostLikes($post->getId());
            $postUser = $this->UserManager->getById($post->getUserId());

            $postHTML = '
    <div class="column is-one-third">
        <div class="card">
            <div class="card-image">
                <figure class="image is-4by3">
                    <img src="/Media/posts/' . $post->getPath() . '.png">
                </figure>
            </div>
            <div class="card-content">
                <div class="media">
                    <div class="media-left">
                        <figure class="image is-48x48">
                            ' . (($postUser->getAvatar()) ? '<img src="/Media/avatars/' . $postUser->getAvatar() . '">' : '<img src="/Media/avatars/avatar.png">') . '
                        </figure>
                    </div>
                    <div class="media-content" onclick="redirect(\'' . $postUser->getUsername() . '\', ' . (($user && $user->getUsername() != $postUser->getUsername()) ? '0' : '1') . ')" style="cursor:pointer">
                        <p class="title is-4">' . $postUser->getFirstname() . ' ' . $postUser->getLastname() . '</p>
                        <p class="subtitle is-6">@' . $postUser->getUsername() . '</p>
                    </div>
                </div>
                <div class="media-bottom" id="media-bottom-' . $i . '">
                    <div class="media-likes">
                        <div class="media-likes-content">
                            <div class="level is-mobile mb-1">
                                <div class="level-left">
                                    ' . (($user != NULL) ? '<form action="/like-gallery" method="POST">
                                        <button class="button mr-2" action="submit">' . (($this->LikesManager->likeExists($post->getId(), $user->getId())) ? '<i class="fa-solid fa-heart"></i>' : '<i class="fa-regular fa-heart"></i>') . '</button>
                                        <input type="hidden" name="post_id" value="' . $post->getId() . '">
                                    </form>' : '') . '
                                    <button class="button" onclick="showComments(' . $i . ')">
                                        <i class="fa-regular fa-comment"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="media-likes-count mb-3">
                                <p class="text is-6">' . count($likes) . ' ' . ((count($likes) < 2) ? 'Like' : 'Likes') . '</p>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        ' . (($post->getDescription()) ? '<p class="text">' . $post->getDescription() . '</p>' : '<br>') . '
                        <p class="date">' . (new DateTime($post->getDate()))->format('h:i A') . ' - ' . (new DateTime($post->getDate()))->format('j F Y') . '</p>
                    </div>
                </div>
                <div class="media-comments is-hidden" id="media-comments-' . $i . '">
                    <div class="container">
                        <div class="columns">
                            <div class="column">
                                <label class="label is-pulled-left mt-2">Commentaires</label>
                            </div>
                            <div class="column">
                                <button class="button is-pulled-right" onclick="hideComments(' . $i . ')">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="media-comments-content">';
            foreach ($comments as $comment) {
                $postHTML .= '
                        <div class="container">
                            <div class="columns">
                                <div class="column is-4">
                                    <label class="label">' . $comment->getUsername() . ':</label>
                                </div>
                                <div class="column is-6">
                                    <p class="text">' . $comment->getComment() . '</p>
                                </div>
                            </div>
                        </div>
                        <hr>';
            }
            $postHTML .= '
                    </div>
                    <div class="container">';
            if ($user != NULL) {
                $postHTML .= '
                        <form action="/add-comment-gallery" method="POST">
                            <div class="columns">
                                <div class="column">
                                    <div class="field">
                                        <div class="control">
                                            <input type="hidden" name="pictureId" value="' . $post->getId() . '" />
                                            <input id="comment_' . $i . '" class="input" name="comment" type="text" placeholder="Commentaire">
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-2">
                                    <button class="button is-fullwidth" type="submit">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </form>';
            }
            $postHTML .= '
                    </div>
                </div>
            </div>
        </div>
    </div>';
            $postsHTML .= $postHTML;
        }

        if (!empty($postsHTML)) {
            http_response_code(200);
            echo $postsHTML;
        } else {
            http_response_code(204);
        }
    }
}