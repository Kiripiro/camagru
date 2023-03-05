<div class="navbar-spacer"></div>
<section class="settings">
    <div class="container">
        <div class="columns">
            <div class="column is-3 is-clipped">
                <aside class="menu is-hidden-mobile" style="position: fixed;">
                    <p class="menu-label">
                        Paramètres
                    </p>
                    <ul class="menu-list">
                        <li><a class="my-profile-menu" href="#my-profile">Modifier mon Profil</a>
                            <ul>
                                <li><a class="profile-pic-menu" href="#profile-pic">Photo</a></li>
                                <li><a class="login-menu" href="#login">Login</a></li>
                                <li><a class="email-menu" href="#email">Email</a></li>
                                <li><a class="biography-menu" href="#biography">Biographie</a></li>
                                <li><a class="delete-menu" href="#delete">Supprimer</a></li>
                            </ul>
                        </li>
                        <li><a class="security-menu" href="#security">Sécurité</a>
                            <ul>
                                <li><a class="update-password-menu" href="#update-password">Mot de passe</a></li>
                            </ul>
                        </li>
                        <li><a class="notifications-menu" href="#notifications">Notifications</a>
                            <ul>
                                <li><a class="email-notifications-menu" href="#email-notifications">Recevoir
                                        par email</a></li>
                            </ul>
                        </li>
                    </ul>
                </aside>
            </div>
            <div class="menu-container column is-9" style="overflow-y: scroll;">
                <section class="settings is-light">
                    <div id="my-profile" class="box">
                        <div class="box">
                            <h3 class="title is-4 has-text-black has-text-centered">Modifier mon profil</h3>
                        </div>
                        <hr id="profile-pic" class="hr">
                        <h5 class="title is-5 has-text-black">Photo</h5>
                        <form id="profile-form" action="/settings/update-avatar" method="post" class="form"
                            enctype="multipart/form-data" accept="image/*">
                            <div class="columns is-vcentered">
                                <div class="column is-3">
                                    <?php if ($user->getAvatar() != null): ?>
                                        <div class="has-text-centered">
                                            <figure class="image is-128x128 is-inline-block">
                                                <img class="image is-rounded" src="/Media/avatars/<?= $user->getAvatar() ?>"
                                                    alt="Logo">
                                            </figure>
                                        </div>
                                    <?php else: ?>
                                        <div class="has-text-centered">
                                            <figure class="image is-128x128 is-inline-block">
                                                <img class="image is-rounded" src="/Media/avatars/avatar.png" alt="Logo">
                                            </figure>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="column is-3">
                                    <div id="file-upload"
                                        class="file is-small is-primary has-name is-boxed is-centered">
                                        <label class="file-label">
                                            <input class="file-input" type="file" name="upload">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                </span>
                                                <span class="file-label">
                                                    Choisir un fichier…
                                                </span>
                                            </span>
                                            <span class="file-name">
                                                Aucun fichier sélectionné
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <button name="update" class="button is-primary">Modifier</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="notification">
                                        La taille recommandée est de <strong>128x128</strong> pixels.
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr id="login" class="hr">
                        <h5 class="title is-5 has-text-black">Login</h5>
                        <form class="form" action="/settings/login" method="post">
                            <div class="columns is-vcentered">
                                <div class="column is-6">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="login" type="text" placeholder="Nouveau login">
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <button class="button is-primary">Modifier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr id="email" class="hr">
                        <h5 class="title is-5 has-text-black">Email</h5>
                        <form class="form" action="/settings/email" method="post">
                            <div class="columns is-vcentered">
                                <div class="column is-6">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="email" type="email" placeholder="Nouvel email">
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <button class="button is-primary">Modifier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr id="biography" class="hr">
                        <h5 class="title is-5 has-text-black">Biographie</h5>
                        <form class="form" action="/settings/biography" method="post">
                            <div class="columns is-vcentered">
                                <div class="column is-6">
                                    <div class="field">
                                        <div class="control">
                                            <textarea class="textarea" name="biography" type="text"
                                                placeholder="Votre biographie"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <button class="button is-primary">Modifier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr id="delete" class="hr">
                        <h5 class="title is-5 has-text-black">Supprimer</h5>
                        <div class="columns is-vcentered">
                            <div class="column is-6">
                                <div class="notification">
                                    Voulez-vous vraiment supprimer votre compte ?<br><br>
                                    Attention, cette action est <strong class="has-text-danger">irréversible</strong>
                                    !<br>
                                    Une fois le compte supprimé, toutes les données seront effacées et ne
                                    pourront pas être récupérées.
                                </div>
                            </div>
                            <div class="column is-3">
                                <div class="field">
                                    <div class="control has-text-centered">
                                        <button id="delete-button" class="button is-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="hr">
                        <div class="box">
                            <h3 id="security" class="title is-4 has-text-black has-text-centered">Sécurité</h3>
                        </div>
                        <hr id="update-password" class="hr">
                        <h5 class="title is-5 has-text-black">Modifier le mot de passe</h5>
                        <form class="form" action="/settings/update-password" method="post">
                            <div class="columns is-vcentered">
                                <div class="column is-6">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="password" type="password"
                                                placeholder="Ancien mot de passe">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="newPassword" type="password"
                                                placeholder="Nouveau mot de passe">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="confirmPassword" type="password"
                                                placeholder="Confirmez le mot de passe">
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <button class="button is-primary">Modifier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr class="hr">
                        <div id="notifications" class="box">
                            <h3 class="title is-4 has-text-black has-text-centered">
                                Notifications
                            </h3>
                        </div>
                        <hr id="email-notifications" class="hr">
                        <h5 class="title is-5 has-text-black">Recevoir par email</h5>
                        <form class="form" action="/settings/update-notifications" method="post">
                            <div class="columns is-vcentered">
                                <div class="column is-6">
                                    <div class="notification">Voulez-vous recevoir un email de notification pour chaque
                                        nouveau commentaire sur vos photos ?
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <?php if ($user->getNotifs() == 0): ?>
                                                <input type="hidden" name="value" value="activated">
                                                <button class="button is-primary">Activer</button>
                                            <?php else: ?>
                                                <input type="hidden" name="value" value="deactivated">
                                                <button class="button is-danger">Désactiver</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                </section>
            </div>
        </div>
    </div>
</section>

<div id="delete-modal" class="modal">
    <div id="settings-modal-background" class="modal-background"></div>
    <div class="modal-card">
        <form class="form" action="/settings/delete" method="post">
            <header class="modal-card-head">
                <h3 class="title is-4 is-centered">Supprimer le compte</h3>
            </header>
            <section class="modal-card-body">
                <p class="has-text-centered">Voulez-vous vraiment <strong class="has-text-danger">supprimer</strong>
                    votre compte de manière permanante ?</p>
                <p class="has-text-centered">Dans ce cas, veuillez entrer votre mot de passe ci-dessous.</p>
                <br>
                <div class="field">
                    <div class="control is-expanded">
                        <input class="input" name="password" type="password" placeholder="Mot de passe">
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot is-centered">
                <button class="button is-danger">Supprimer</button>
            </footer>
        </form>
    </div>
</div>
<div id="snackbar" class="notification" hidden>
    <button id="snackbar-delete" class="delete"></button>
    <span id="snackbar-message"></span>
</div>
<?php
if (isset($success_message)) {
    $js_content = "showSnackbar('" . $success_message . "', '" . ('success') . "')";
    $session->remove('success_message');
} else if (isset($error_message)) {
    $js_content = "showSnackbar('" . $error_message . "', '" . ('danger') . "')";
    $session->remove($error_message);
} else
    $js_content = null;
?>

<script>
    <?php if ($js_content)
        echo $js_content; ?>
</script>