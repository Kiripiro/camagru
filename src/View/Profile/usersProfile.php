<?
include_once('Utils/snackbar.php');
?>
<div class="navbar-spacer"></div>
<section class="profile">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-9">
                <div class="profile-container is-centered">
                    <section class="profile-info is-light">
                        <div class="box">
                            <div class="box">
                                <h3 class="title is-4 has-text-black has-text-centered">
                                    <?= $userProfile->getUsername() ?>
                                </h3>
                            </div>
                            <div class="box">
                                <div class="columns">
                                    <div class="profile-avatar column is-3">
                                        <div class="profile-avatar-container">
                                            <div class="has-text-centered">
                                                <figure class="image is-128x128 is-inline-block">
                                                    <?php if (isset($userProfile) && $userProfile->getAvatar() != null): ?>
                                                        <img class="image is-rounded"
                                                            src="/Media/avatars/<?= $userProfile->getAvatar() ?>"
                                                            alt="Logo">
                                                    <?php else: ?>
                                                        <img class="image is-rounded" src="/Media/avatars/avatar.png"
                                                            alt="Logo">
                                                    <? endif; ?>
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-infos column is-9">
                                        <div class="profile-infos-container">
                                            <div class="columns">
                                                <div class="column is-3">
                                                    <label class="label">Username</label>
                                                    <p class="text is-6">
                                                        <?= $userProfile->getUsername() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Firstname</label>
                                                    <p class="text is-6">
                                                        <?= $userProfile->getFirstname() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Lastname</label>
                                                    <p class="text is-6">
                                                        <?= $userProfile->getLastname() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Posts</label>
                                                    <p class="text is-6">
                                                        <?= $nb_posts ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-infos-bis-container">
                                                <label class="label">Biography</label>
                                                <p class="text is-6 biography">
                                                    <?= $userProfile->getBiography() ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </section>
                    <section class="profile-posts is-light">
                        <div class="box mt-5">
                            <h3 class="title is-4 has-text-black has-text-centered">Posts</h3>
                            <?php if (isset($posts) && !empty($posts)) {
                                foreach ($posts as $post) {
                                    $filename = "Media/posts/" . $post["path"] . ".png";
                                    if (file_exists($filename)) {
                                        echo '
                                                <div id="box_' . $post["id"] . '" class="box post mb-2">
                                                    <div id="post_' . $post["id"] . '" class="post-container">
                                                        <div class="control">
                                                            <figure class="image is-square">
                                                                <img src="/Media/posts/' . $post["path"] . '.png" alt="Image" data-post-id="' . $post["path"] . '"/>
                                                            </figure>
                                                        </div>
                                                        <div class="control">
                                                            <div class="level is-mobile">
                                                                <div class="level-left mt-2">';
                                        if ($userProfile != NULL) {
                                            echo '<button class="button mr-2 mt-2" onclick="likePost(' . $post['id'] . ')">';
                                            if ($post['liked']) {
                                                echo '<i id="unlike-' . $post['id'] . '" class="fa-solid fa-heart"></i>'
                                                    . '<i id="like-' . $post['id'] . '" class="fa-regular fa-heart is-hidden"></i>';
                                            } else {
                                                echo '<i id="like-' . $post['id'] . '" class="fa-regular fa-heart"></i>'
                                                    . '<i id="unlike-' . $post['id'] . '" class="fa-solid fa-heart is-hidden"></i>';
                                            }
                                            echo '</button>';
                                        }

                                        echo '  <button class="button mt-2" onclick="showComments(' . $post["id"] . ')">
                                                    <i class="fa-regular fa-comment"></i>
                                                </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control">
                                                <div class="media-likes-comments-count mt-3">';
                                        if ($post["likes"] < 2)
                                            echo '<p id="like-count-' . $post['id'] . '" class="text is-6">' . $post["likes"] . ' Like' . '</p>';
                                        else
                                            echo '<p id="like-count-' . $post['id'] . '" class="text is-6">' . $post["likes"] . ' Likes' . '</p>';
                                        if ($post["comments_count"] < 2)
                                            echo '<p id="comment-count-' . $post['id'] . '" class="text is-6">' . $post["comments_count"] . ' Comment' . '</p>';
                                        else
                                            echo '<p id="comment-count-' . $post['id'] . '" class="text is-6">' . $post["comments_count"] . ' Comments' . '</p>';
                                        echo '</div>
                                                    </div>
                                                        <div class="control">
                                                            <label class="label mt-2">Description</label>
                                                            <label class="description is-6">' . $post["description"] . '</label>
                                                        </div>
                                                    </div>
                                                    <div id="comments_' . $post["id"] . '" class="comments is-hidden">
                                                        <div class="container">
                                                            <div class="columns is-mobile">
                                                                <div class="column">
                                                                    <label class="label is-pulled-left mt-3">Comments</label>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <button class="button is-pulled-right" onclick="hideComments(' . $post["id"] . ')">
                                                                        <i class="fa-solid fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div id="comments-' . $post['id'] . '" class="media-comments-content">
                                            ';
                                        $i = 0;
                                        foreach ($post["comments"] as $comment) {
                                            if ($i != 0)
                                                echo '<hr>';
                                            echo '
                                                <div class="comments container">
                                                    <div class="columns is-mobile">
                                                        <div class="column is-3">
                                                            <label id="comment-user-' . $post['id'] . '" class="label">' . $comment->getUsername() . ':</label>
                                                        </div>
                                                        <div class="column is-7">
                                                            <p id="comment-' . $post['id'] . '" class="comment">' . $comment->getComment() . '</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                ';
                                            $i++;
                                        }
                                        echo '
                                                    </div>
                                                    <hr>
                                                    <div class="columns is-mobile">
                                                        <div class="column">
                                                            <div class="field">
                                                                <div class="control">
                                                                    <input type="hidden" name="pictureId" value="' . $post["id"] . '" />
                                                                    <input id="new-comment-' . $post['id'] . '" class="input" name="comment" type="text" maxlength="255" placeholder="Comments" onkeypress="handleKeyPressComment(event, ' . $post['id'] . ' )">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="column is-2">
                                                            <button class="button is-fullwidth" type="submit" onclick="addComment(' . $post['id'] . ')">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ';
                                    }
                                }
                            } else {
                                echo 'No posts yet.';
                            }
                            ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>