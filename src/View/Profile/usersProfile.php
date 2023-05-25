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
                                <h3 class="title is-4 has-text-black has-text-centered">Profil</h3>
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
                                                    <label class="label">Login</label>
                                                    <p class="text is-6">
                                                        <?= $userProfile->getLogin() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Prénom</label>
                                                    <p class="text is-6">
                                                        <?= $userProfile->getFirstname() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Nom</label>
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
                                                <label class="label">Biographie</label>
                                                <p class="text is-6">
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
                                $i = 0;
                                foreach ($posts as $post) {
                                    $i++;
                                    $filename = "Media/posts/" . $post["path"] . ".png";
                                    if (file_exists($filename)) {
                                        echo '
                                                <div id="box_' . $i . '" class="box post mb-2">
                                                    <div id="post_' . $i . '" class="post-container">
                                                        <div class="control">
                                                            <figure class="image is-4by3">
                                                                <img src="/Media/posts/' . $post["path"] . '.png" alt="Image" data-post-id="' . $post["path"] . '"/>
                                                            </figure>
                                                        </div>
                                                        <div class="control">
                                                            <div class="level is-mobile">
                                                                <div class="level-left mt-2">
                                                                    <form action="/like" method="post">
                                                                        <button class="button mt-2 mr-1" action="submit">';
                                        if ($post['liked']) {
                                            echo '<i class="fa-solid fa-heart"></i>';
                                        } else {
                                            echo '<i class="fa-regular fa-heart"></i>';
                                        }
                                        echo '</button>
                                                                        <input type="hidden" name="pictureId" value="' . $post["id"] . '">
                                                                        <input type="hidden" name="userLogin" value="' . $userProfile->getLogin() . '">
                                                                    </form>
                                                                    <button class="button mt-2" onclick="showComments(' . $i . ')">
                                                                        <i class="fa-regular fa-comment"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="control">
                                                                <div class="media-likes-comments-count mt-3">';
                                        if ($post["likes"] < 2)
                                            echo '<p class="text is-6">' . $post["likes"] . ' Like' . '</p>';
                                        else
                                            echo '<p class="text is-6">' . $post["likes"] . ' Likes' . '</p><';
                                        if ($post["comments_count"] < 2)
                                            echo '<p class="text is-6">' . $post["comments_count"] . ' Commentaire' . '</p>';
                                        else
                                            echo '<p class="text is-6">' . $post["comments_count"] . ' Commentaires' . '</p>';
                                        echo '</div>
                                                                
                                                        </div>
                                                        <div class="control">
                                                            <label class="label mt-2">Description</label>
                                                            <label class="text is-6">' . $post["description"] . '</label>
                                                        </div>
                                                    </div>
                                                    <div id="comments_' . $i . '" class="comments is-hidden">
                                                        <div class="container">
                                                            <div class="columns">
                                                                <div class="column">
                                                                    <label class="label is-pulled-left mt-3">Commentaires</label>
                                                                </div>
                                                                <div class="column is-2">
                                                                    <button class="button is-pulled-right" onclick="hideComments(' . $i . ')">
                                                                        <i class="fa-solid fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="container">
                                            ';
                                        foreach ($post["comments"] as $comment) {
                                            echo '
                                                    <div class="columns">
                                                        <div class="column is-2">
                                                            <label class="label">' . $comment->getUserLogin() . ':</label>
                                                        </div>
                                                        <div class="column is-8">
                                                            <p class="text">' . $comment->getComment() . '</p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                ';
                                        }
                                        ;
                                        echo ' 
                                                        </div>
                                                        <div class="container">
                                                            <form action="/add-comment" method="POST">
                                                                <div class="columns">
                                                                    <div class="column">
                                                                        <div class="field">
                                                                            <div class="control">
                                                                                <input type="hidden" name="pictureId" value="' . $post["id"] . '" />
                                                                                <input type="hidden" name="userLogin" value="' . $userProfile->getLogin() . '" />
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
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            ';
                                    }
                                }
                            } else {
                                echo 'Aucune photo';
                            }
                            ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>