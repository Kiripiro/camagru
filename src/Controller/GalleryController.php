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
                    "message" => "Invalid token"
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
            $return = "An error has occured. Please try again.";
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
                    "message" => "Invalid token"
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
            echo json_encode(array("error" => "Please enter a comment."));
            exit;
        }
        if (strlen($comment) > 255) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array("error" => "Comment too long."));
            exit;
        }
        $comment = htmlspecialchars($comment);
        $return = $this->CommentsManager->addComment($comment, $postId, $user->getId());
        if ($return === false) {
            $return = "An error has occured. Please try again.";
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array("error" => $return));
            exit;
        } else if ($return === "Comment has been added") {
            $post = $this->StudioManager->getById($postId);
            $userPost = $this->UserManager->getById($post->getUserId());
            if ($userPost->getNotifs() == 1) {
                $mail = new Mail();
                if (!$mail->sendNewCommentMail($userPost->getFirstname(), $userPost->getLastname(), $userPost->getEmail())) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(array("error" => "An error has occured. Please try again."));
                    exit;
                } else {
                    $response = array(
                        "success" => true,
                        "message" => $return,
                        "mail" => "Mail sent",
                        "user" => $user->getUsername(),
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
                    "user" => $user->getUsername(),
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
                    "message" => "Invalid token"
                );
                return $response;
            }
        }

        $allPosts = $this->StudioManager->getNbPosts($offset, 9);
        $postsHTML = '';

        foreach ($allPosts as $post) {
            $comments = $this->CommentsManager->getPostComments($post->getId());

            foreach ($comments as $comment) {
                $comment->setUsername($this->UserManager->getById($comment->getUser_id())->getUsername());
            }

            $likes = $this->LikesManager->getPostLikes($post->getId());
            $postUser = $this->UserManager->getById($post->getUserId());
            if ($user)
                $liked = $this->LikesManager->likeExists($post->getId(), $user->getId());
            $postHTML = '
        <div class="column is-one-third">
            <div class="card">
                <div class="card-image">
                    <figure class="image is-100x100">
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
                    <div class="media-bottom" id="media-bottom-' . $post->getId() . '">
                        <div class="media-likes">
                            <div class="media-likes-content">
                                <div class="level is-mobile mb-1">
                                    <div class="level-left">
                                        ' . (($user != NULL) ? '<button class="button mr-2" onclick="likePost(' . $post->getId() . ')">' . (($liked) ? '<i id="unlike-' . $post->getId() . '" class="fa-solid fa-heart"></i>'
                                        . '<i id="like-' . $post->getId() . '" class="fa-regular fa-heart is-hidden"></i></button>' : '<i id="like-' . $post->getId() . '" class="fa-regular fa-heart"></i>'
                                        . '<i id="unlike-' . $post->getId() . '" class="fa-solid fa-heart is-hidden"></i></button>') : '')
                                        . '
                                        <button class="button" onclick="showComments(' . $post->getId() . ')">
                                            <i class="fa-regular fa-comment"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="media-likes-count mb-3">
                                    <p id="like-count-' . $post->getId() . '" class="text is-6">' . count($likes) . ' ' . ((count($likes) < 2) ? 'Like' : 'Likes') . '</p>
                                    <p id="comment-count-' . $post->getId() . '" class="text is-6">' . count($comments) . ' ' . ((count($comments) < 2) ? 'Comment' : 'Comments') . '</p>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            ' . (($post->getDescription()) ? '<p class="description">' . $post->getDescription() . '</p>' : '<br>') . '
                            <p class="mt-4 date">' . (new DateTime($post->getDate()))->format('h:i A') . ' - ' . (new DateTime($post->getDate()))->format('j F Y') . '</p>
                        </div>
                    </div>
                    <div class="media-comments is-hidden" id="media-comments-' . $post->getId() . '">
                        <div class="container">
                            <div class="columns is-mobile">
                                <div class="column">
                                    <label class="label is-pulled-left mt-2">Comments</label>
                                </div>
                                <div class="column">
                                    <button class="button is-pulled-right" onclick="hideComments(' . $post->getId() . ')">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="comments-' . $post->getId() . '" class="media-comments-content">';
            $i = 0;
            foreach ($comments as $comment) {
                if ($i != 0)
                    $postHTML .= '<hr>';
                $postHTML .= '
                            <div class="container">
                                <div class="columns is-mobile">
                                    <div class="column is-3">
                                        <label class="label">' . $comment->getUsername() . ':</label>
                                    </div>
                                    <div class="column is-7">
                                        <p class="comment">' . $comment->getComment() . '</p>
                                    </div>
                                </div>
                            </div>';
                        $i++;
            }
            $postHTML .= '
                        </div>
                        <hr>
                        <div class="container comment-input">';
            if ($user != NULL) {
                $postHTML .= '
                            <div class="columns is-mobile">
                                <div class="column">
                                    <div class="field">
                                        <div class="control">
                                            <input type="hidden" name="pictureId" value="' . $post->getId() . '" />
                                            <input id="new-comment-' . $post->getId() . '" class="input" name="comment" type="text" maxlength="255" placeholder="Comments" onkeypress="handleKeyPressComment(event, ' . $post->getId() . ' )">
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-2">
                                    <button class="button is-fullwidth" type="submit" onclick="addComment(' . $post->getId() . ')">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>';
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