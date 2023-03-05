<div class="navbar-spacer"></div>
<div class="container is-flex">
    <div class="columns">
        <div class="column is-two-third">
            <canvas id="canvas" class="is-responsive" style="border: 1px solid black;"></canvas>
            <video id="video" autoPlay={true} playsInline={true} muted={true} style="display:none;"></video>
            <div class="field">
                <div class="control">
                    <button class="button is-primary" onclick="takeSnapshot()">Prendre la photo</button>
                </div>
            </div>
        </div>

        <div class="column is-2">
            <div class="filters">
                <div class="field">
                    <label class="label">Filtres</label>
                    <!-- <div class="is-one-quarter">
                        <figure class="image is-4by3">
                            <img class="filter" src="/Media/filtres/sad.jpg" alt="Image" onclick="addFilter(this)" />
                        </figure>
                    </div> -->
                    <div class="is-one-quarter">
                        <figure class="image">
                            <img class="filter" src="/Media/filtres/pikachoum.png" alt="Image"
                                onclick="addFilter(this)" />
                        </figure>
                    </div>
                    <div class="is-one-quarter">
                        <figure class="image">
                            <img class="filter" src="/Media/filtres/doggo.png" alt="Image" onclick="addFilter(this)" />
                        </figure>
                    </div>
                    <div class="is-one-quarter">
                        <figure class="image">
                            <img class="filter" src="/Media/filtres/blob.png" alt="Image" onclick="addFilter(this)" />
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <section class="new-picture">
    <div class="container">
        <div class="columns is-centered is-mobile">
            <div class="column">
                <div class="new-picture-container">
                    <section class="new-picture-info">
                        <div class="box">
                            <div class="box">
                                <h3 class="title is-4 has-text-black has-text-centered">Ajouter une image</h3>
                            </div>
                            <div class="box">
                                <div class="columns">
                                    <div class="column is-5">
                                        <div class="new-picture-form-container">
                                            <form action="/add-picture" method="post" enctype="multipart/form-data">
                                                <label class="label">Aperçu</label>
                                                <div class="photo-box">
                                                    <video id="video" autoplay="true"></video>
                                                    <img id="photo" src="" alt="Photo" hidden />
                                                    <canvas id="canvas"></canvas>
                                                </div>
                                                <div class="field">
                                                    <div class="control has-text-centered mt-2">
                                                        <button class="button is-primary"
                                                            onclick="takeSnapshot()">Prendre
                                                            la
                                                            photo</button>
                                                    </div>
                                                </div>
                                                <hr class="studio-hr" data-content="OU">
                                                <div class="field">
                                                    <label class="label">Importer une image</label>
                                                    <div class="control">
                                                        <input class="input" type="file" name="image" />
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label class="label">Ajouter une description</label>
                                                    <div class="control">
                                                        <textarea class="textarea" name="description"
                                                            placeholder="Description"></textarea>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="control has-text-centered">
                                                        <button class="button is-primary">Ajouter</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="column is-2">
                                        <div class="filters">
                                            <div class="field">
                                                <label class="label">Filtres</label>
                                                <div class="is-one-quarter">
                                                    <figure class="image is-4by3">
                                                        <img class="filter" src="/Media/filtres/sad.jpg" alt="Image"
                                                            onclick="addFilter(this)" />
                                                    </figure>
                                                </div>
                                                <div class="is-one-quarter">
                                                    <figure class="image is-4by3">
                                                        <img class="filter" src="/Media/filtres/pikachoum.jpg"
                                                            alt="Image" onclick="addFilter(this)" />
                                                    </figure>
                                                </div>
                                                <div class="is-one-quarter">
                                                    <figure class="image is-4by3">
                                                        <img class="filter" src="/Media/filtres/doggo.jpg" alt="Image"
                                                            onclick="addFilter(this)" />
                                                    </figure>
                                                </div>
                                                <div class="is-one-quarter">
                                                    <figure class="image is-4by3">
                                                        <img class="filter" src="/Media/filtres/kappa.jpg" alt="Image"
                                                            onclick="addFilter(this)" />
                                                    </figure>
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
                                                            <button
                                                                class="button is-danger is-fullwidth">Supprimer</button>
                                                        </div>
                                                    </div>
                                                    <div class="control">
                                                        <figure class="image is-4by3">
                                                            <img src="https://picsum.photos/300/200" alt="Image" />
                                                        </figure>
                                                    </div>
                                                    <div class="field">
                                                        <div class="control">
                                                            <button
                                                                class="button is-danger is-fullwidth">Supprimer</button>
                                                        </div>
                                                    </div>
                                                    <div class="control">
                                                        <figure class="image is-4by3">
                                                            <img src="https://picsum.photos/100/100" alt="Image" />
                                                        </figure>
                                                    </div>
                                                    <div class="field">
                                                        <div class="control">
                                                            <button
                                                                class="button is-danger is-fullwidth">Supprimer</button>
                                                        </div>
                                                    </div>
                                                    <div class="control">
                                                        <figure class="image is-4by3">
                                                            <img src="https://picsum.photos/300/300" alt="Image" />
                                                        </figure>
                                                    </div>
                                                    <div class="field">
                                                        <div class="control">
                                                            <button
                                                                class="button is-danger is-fullwidth">Supprimer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section> -->