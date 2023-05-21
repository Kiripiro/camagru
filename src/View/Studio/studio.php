<?
include_once('Utils/snackbar.php');
?>
<div class="navbar-spacer"></div>
<div class="container">
    <div id="box" class="box">
        <div class="box">
            <h3 class="title is-4 has-text-black has-text-centered">Studio</h3>
        </div>
        <div class="create-image">
            <div class="columns">
                <div class="column is-7">
                    <div id="studio-container" class="container">
                        <div id="box" class="box">
                            <video id="video" autoPlay={true} playsInline={true} muted={true}></video>
                            <canvas id="canvas"></canvas>
                        </div>
                        <div class="field is-grouped is-flex is-justify-content-center">
                            <div id="use-image" class="control is-hidden">
                                <button class="button is-link is-mobile-responsive" onclick="useImage()">Utiliser la
                                    photo</button>
                            </div>
                            <div class="control take-snapshot">
                                <button id="take-snapshot" class="button is-primary is-mobile-responsive"
                                    onclick="takeSnapshot()">Prendre la photo</button>
                            </div>
                            <div class="control">
                                <button id="delete-filter" class="button is-warning is-mobile-responsive"
                                    onclick="deleteFilter()" disabled>Supprimer le filtre</button>
                            </div>
                            <div class="control">
                                <button id="delete-photo" class="button is-danger is-mobile-responsive"
                                    onclick="deletePhoto()" disabled>Supprimer la photo</button>
                            </div>
                        </div>
                        <hr class="studio-hr">
                        <div class="field">
                            <label class="label">Importer une image</label>
                            <div class="control">
                                <input id="file-upload" class="input" type="file" name="image" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-2">
                    <div class="filters">
                        <div class="field">
                            <label class="label">Filtres</label>
                            <div class="filters-container" style="overflow-y: scroll; height: 75vh">
                                <div class="is-one-quarter">
                                    <?php
                                    if (isset($latest_posts))
                                        echo 'oui';
                                    ?>
                                    <figure class="image">
                                        <img class="filter" src="/Media/filtres/issou.png" alt="Image"
                                            onclick="addFilter(this)" />
                                    </figure>
                                </div>
                                <div class="is-one-quarter">
                                    <figure class="image">
                                        <img class="filter" src="/Media/filtres/booba.png" alt="Image"
                                            onclick="addFilter(this)" />
                                    </figure>
                                </div>
                                <div class="is-one-quarter">
                                    <figure class="image">
                                        <img class="filter" src="/Media/filtres/troll.png" alt="Image"
                                            onclick="addFilter(this)" />
                                    </figure>
                                </div>
                                <div class="is-one-quarter">
                                    <figure class="image">
                                        <img class="filter" src="/Media/filtres/doggo.png" alt="Image"
                                            onclick="addFilter(this)" />
                                    </figure>
                                </div>
                                <div class="is-one-quarter">
                                    <figure class="image">
                                        <img class="filter" src="/Media/filtres/kappa.png" alt="Image"
                                            onclick="addFilter(this)" />
                                    </figure>
                                </div>
                                <div class="is-one-quarter">
                                    <figure class="image">
                                        <img class="filter" src="/Media/filtres/monkey.png" alt="Image"
                                            onclick="addFilter(this)" />
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-3">
                    <div class="recent">
                        <div class="field">
                            <label class="label">Dernières prises</label>
                            <div class="recent-pics" style="overflow-y: scroll; height: 100vh">
                                <?php if (isset($posts) && !empty($posts)) {
                                    foreach ($posts as $post) {
                                        $filename = "Media/posts/" . $post . ".png";
                                        if (file_exists($filename)) {
                                            echo '<div class="control">
                                                    <figure class="image is-100x100">
                                                        <img src="/Media/posts/' . $post . '.png" alt="Image" data-post-id="' . $post . '"/>
                                                    </figure>'
                                                . '<div class="field mb-2">
                                                        <div class="control">
                                                            <button class="button is-danger is-fullwidth" onclick="deletePost(this)">Supprimer</button>
                                                        </div>
                                                    </div>
                                                </div>';
                                        }
                                    }
                                } else {
                                    echo 'Aucune photo';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="upload-image is-hidden">
            <div class="box">
                <form action="/studio-upload" method="POST">
                    <img id="image" alt="Image" />
                    <div class="field">
                        <label class="label">Description</label>
                        <div class="control">
                            <textarea class="textarea" name="description"
                                placeholder="Description de l'image"></textarea>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button class="button is-link">Publier</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>