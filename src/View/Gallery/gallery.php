<?
include_once('Utils/snackbar.php');
?>
<div class="navbar-spacer"></div>
<section class="gallery-section">
    <div class="gallery container">
        <div id="gallery" class="columns is-multiline">
            <? if (isset($posts)) { ?>
                <? $i = count($posts); ?>
                <? foreach ($posts as $post) { ?>
                    <div class="column is-one-third">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-100x100">
                                    <?php echo '<img src="/Media/posts/' . $post["path"] . '.png">'; ?>
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="media">
                                    <div class="media-left">
                                        <figure class="image is-48x48">
                                            <?
                                            if (isset($post["user_avatar"]))
                                                echo '<img src="/Media/avatars/' . $post["user_avatar"] . '">';
                                            else
                                                echo '<img src="/Media/avatars/avatar.png">';
                                            ?>
                                        </figure>
                                    </div>
                                    <?
                                    if (isset($user)) {
                                        if ($user && $user->getUsername() != $post['username'])
                                            echo '<div class="media-content" onclick="redirect(`' . $post['username'] . '`, 0)" style="cursor:pointer">';
                                        else
                                            echo '<div class="media-content" onclick="redirect(`' . $post['username'] . '`, 1)" style="cursor:pointer">';
                                    } else {
                                        echo '<div class="media-content">';
                                    }
                                    ?>
                                    <?php echo '<p class="title is-4">' . $post["user_firstname"] . " " . $post["user_lastname"] . '</p>'; ?>
                                    <? echo '<p class="subtitle is-6">@' . $post['username'] . '</p>'; ?>
                                </div>
                            </div>
                            <? echo '<div id="media-bottom-' . $post['id'] . '" class="media-bottom">'; ?>
                            <div class="media-likes">
                                <div class="media-likes-content">
                                    <div class="level is-mobile mb-1">
                                        <div class="level-left">
                                            <?php if ($user != NULL) {
                                                echo '<button class="button mr-2" onclick="likePost(' . $post['id'] . ')">';
                                                if ($post['liked']) {
                                                    echo '<i id="unlike-' . $post['id'] . '" class="fa-solid fa-heart"></i>'
                                                        . '<i id="like-' . $post['id'] . '" class="fa-regular fa-heart is-hidden"></i>';
                                                } else {
                                                    echo '<i id="like-' . $post['id'] . '" class="fa-regular fa-heart"></i>'
                                                        . '<i id="unlike-' . $post['id'] . '" class="fa-solid fa-heart is-hidden"></i>';
                                                }
                                                echo '</button>';
                                            }
                                            ?>
                                            <?php echo '<button class="button" onclick="showComments(' . $post['id'] . ')">'; ?>
                                            <i class="fa-regular fa-comment"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="media-likes-count mb-3">
                                        <?php
                                        if ($post["likes"] < 2)
                                            echo '<p id="like-count-' . $post['id'] . '" class="text is-6">' . $post["likes"] . ' Like' . '</p>';
                                        else
                                            echo '<p id="like-count-' . $post['id'] . '" class="text is-6">' . $post["likes"] . ' Likes' . '</p>';
                                        if ($post["comments_count"] < 2)
                                            echo '<p id="comment-count-' . $post['id'] . '" class="text is-6">' . $post["comments_count"] . ' Comment' . '</p>';
                                        else
                                            echo '<p id="comment-count-' . $post['id'] . '" class="text is-6">' . $post["comments_count"] . ' Comments' . '</p>';
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <div class="content">
                                <?php
                                $date_post = new DateTime($post["date"]);
                                $time = $date_post->format('h:i A');
                                $date = $date_post->format('j F Y');
                                if (!empty($post["description"])) {
                                    echo '<p class="text">' . $post["description"] . '</p>';
                                    echo '<p class="date">' . $time . ' - ' . $date . '</p>';
                                } else {
                                    echo ' <br>';
                                    echo '<p class="mt-4 date">' . $time . ' - ' . $date . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <?
                        echo '<div id="media-comments-' . $post['id'] . '" class="media-comments is-hidden">
                                    <div class="container">
                                        <div class="columns">
                                            <div class="column">
                                                <label class="label is-pulled-left mt-2">Comments</label>
                                            </div>
                                            <div class="column">
                                                <button class="button is-pulled-right" onclick="hideComments(' . $post['id'] . ')">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div id="comments-' . $post['id'] . '" class="media-comments-content">';
                        foreach ($post["comments"] as $comment) {
                            echo '
                                    <div class="container">
                                        <div class="columns">
                                            <div class="column is-3">
                                                <label id="comment-user-' . $post['id'] . '" class="label">' . $comment->getUsername() . ':</label>
                                            </div>
                                            <div class="column is-7">
                                                <p id="comment-' . $post['id'] . '" class="text">' . $comment->getComment() . '</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                ';
                        }
                        echo '  </div>
                                <div class="container">';
                        if ($user != NULL) {
                            echo '<div class="columns">
                                    <div class="column">
                                        <div class="field">
                                            <div class="control">
                                                <input type="hidden" name="pictureId" value="' . $post["id"] . '" />
                                                <input id="new-comment-' . $post['id'] . '" class="input" name="comment" type="text" placeholder="Comment" onkeypress="handleKeyPressComment(event, ' . $post['id'] . ' )">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-2">
                                        <button class="button is-fullwidth" type="submit" onclick="addComment(' . $post['id'] . ')">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>';
                        }
                        echo '
                                </div>
                            </div>
                        ';
                        ?>
                    </div>
                </div>
            </div>
            <? $i--; ?>
        <?php } ?>
    <?php } ?>
    </div>
    <div id="sentinel" class="spinner">
        <div class="lds-ring-gallery is-hidden">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    </div>
</section>