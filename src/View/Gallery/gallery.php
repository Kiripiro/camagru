<?
include_once('Utils/snackbar.php');
?>
<div class="navbar-spacer"></div>
<section class="gallery-section">
    <div class="gallery container">
        <div class="columns is-multiline">
            <?php if (isset($posts)) { ?>
                <? $i = 0; ?>
                <?php foreach ($posts as $post) { ?>
                    <? $i++; ?>
                    <div class="column  is-one-third">
                        <div class="card" onclik="" style="cursor:pointer;">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <?php echo '<img src="/Media/posts/' . $post["path"] . '.png">'; ?>
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="media">
                                    <div class="media-left">
                                        <figure class="image is-48x48">
                                            <?php
                                            if (isset($post["user_avatar"]))
                                                echo '<img src="/Media/avatars/' . $post["user_avatar"] . '">';
                                            else
                                                echo '<img src="/Media/avatars/avatar.png">';
                                            ?>
                                        </figure>
                                    </div>
                                    <div class="media-content" onclick="">
                                        <?php echo '<p class="title is-4">' . $post["user_firstname"] . " " . $post["user_lastname"] . '</p>'; ?>
                                        <?php echo '<p class="subtitle is-6">@' . $post["user_login"] . '</p>'; ?>
                                    </div>
                                </div>
                                <? echo '<div id="media-bottom-' . $i . '" class="media-bottom">'; ?>
                                <div class="media-likes">
                                    <div class="media-likes-content">
                                        <div class="level is-mobile mb-1">
                                            <div class="level-left">
                                                <?php if ($user != NULL) {
                                                    echo '<form action="/like-gallery" method="POST">
                                                    <button class="button mr-2" action="submit">
                                                        <i class="fa-regular fa-heart"></i>
                                                    </button>
                                                        <input type="hidden" name="post_id" value="' . $post["id"] . '">
                                                </form>';
                                                }
                                                ?>
                                                <?php echo '<button class="button" onclick="showComments(' . $i . ')">'; ?>
                                                <i class="fa-regular fa-comment"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="media-likes-count mb-3">
                                            <?php echo '<p class="text is-6">' . $post["likes"] . ' Likes' . '</p>'; ?>
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
                            echo '<div id="media-comments-' . $i . '" class="media-comments is-hidden">
                                <div class="container">
                                    <div class="columns">
                                        <div class="column">
                                            <label class="label is-pulled-left mt-3">Commentaires</label>
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
                            foreach ($post["comments"] as $comment) {
                                echo '
                                    <div class="container">
                                        <div class="columns">
                                            <div class="column">
                                                <label class="label">' . $comment->getUserLogin() . ':</label>
                                            </div>
                                            <div class="column">
                                                <p class="text">' . $comment->getComment() . '</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                ';
                            }
                            echo '
                                    </div>
                                    <div class="container">';
                            if ($user != NULL) {
                                echo '
                                        <form action="/add-comment-gallery" method="POST">
                                            <div class="columns">
                                                <div class="column">
                                                    <div class="field">
                                                        <div class="control">
                                                            <input type="hidden" name="pictureId" value="' . $post["id"] . '" />
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
                            echo '
                                    </div>
                                </div>
                                ';
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</section>