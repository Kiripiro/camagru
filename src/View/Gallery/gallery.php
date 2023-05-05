<?
include_once('Utils/snackbar.php');
?>
<div class="navbar-spacer"></div>
<section class="gallery-section">
    <div class="gallery container">
        <div class="columns is-multiline">
            <?php if (isset($posts)) { ?>
                <?php foreach ($posts as $post) { ?>
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
                                <div class="media-likes">
                                    <div class="media-likes-content">
                                        <div class="level is-mobile mb-1">
                                            <div class="level-left mt-2">
                                                <form action="/like-gallery" method="POST">
                                                    <button class="button mr-2" action="submit">
                                                        <i class="fa-regular fa-heart"></i>
                                                    </button>
                                                    <?php echo '<input type="hidden" name="post_id" value="' . $post["id"] . '">'; ?>
                                                </form>
                                                <button class="button" onclick="">
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
                        </div>
                    </div>

                <?php } ?>
            <?php } ?>
        </div>
</section>