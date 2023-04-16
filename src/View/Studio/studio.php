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
                                <button class="button is-link is-fullwidth is-size-7-mobile"
                                    onclick="useImage()">Utiliser la
                                    photo</button>
                            </div>
                            <div class="control take-snapshot">
                                <button id="take-snapshot" class="button is-primary is-fullwidth is-size-7-mobile"
                                    onclick="takeSnapshot()">Prendre
                                    la photo</button>
                            </div>
                            <div class="control">
                                <button id="delete-filter" class="button is-warning is-fullwidth is-size-7-mobile"
                                    onclick="deleteFilter()" disabled>Supprimer le filtre</button>
                            </div>
                            <div class="control">
                                <button id="delete-photo" class="button is-danger is-fullwidth is-size-7-mobile"
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
                            <div class="filters-container" style="overflow-y: scroll; height: 100vh">
                                <div class="is-one-quarter">
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
                                <div class="control">
                                    <figure class="image is-4by3">
                                        <img src="https://picsum.photos/300/300" alt="Image" />
                                    </figure>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-danger is-fullwidth">Supprimer</button>
                                    </div>
                                </div>
                                <div class="control">
                                    <figure class="image is-4by3">
                                        <img src="https://picsum.photos/300/200" alt="Image" />
                                    </figure>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-danger is-fullwidth">Supprimer</button>
                                    </div>
                                </div>
                                <div class="control">
                                    <figure class="image is-4by3">
                                        <img src="https://picsum.photos/100/100" alt="Image" />
                                    </figure>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-danger is-fullwidth">Supprimer</button>
                                    </div>
                                </div>
                                <div class="control">
                                    <figure class="image is-4by3">
                                        <img src="https://picsum.photos/300/300" alt="Image" />
                                    </figure>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button class="button is-danger is-fullwidth">Supprimer</button>
                                    </div>
                                </div>
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
<?
include_once('Utils/snackbar.php');
?>