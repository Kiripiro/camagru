<div class="navbar-spacer"></div>
<section class="settings">
    <div class="container">
        <div class="columns">
            <div class="column is-3 is-clipped">
                <aside class="menu is-hidden-mobile" style="position: fixed;">
                    <p class="menu-label">
                        Settings
                    </p>
                    <ul class="menu-list">
                        <li><a class="my-profile-menu" href="#my-profile">Update your Profile</a>
                            <ul>
                                <li><a class="profile-pic-menu" href="#profile-pic">Photo</a></li>
                                <li><a class="login-menu" href="#username">Username</a></li>
                                <li><a class="email-menu" href="#email">Email</a></li>
                                <li><a class="biography-menu" href="#biography">Biography</a></li>
                                <li><a class="delete-menu" href="#delete">Delete</a></li>
                            </ul>
                        </li>
                        <li><a class="security-menu" href="#security">Safety</a>
                            <ul>
                                <li><a class="update-password-menu" href="#update-password">Password</a></li>
                            </ul>
                        </li>
                        <li><a class="notifications-menu" href="#notifications">Notifications</a>
                            <ul>
                                <li><a class="email-notifications-menu" href="#email-notifications">Recieve by email</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </aside>
            </div>
            <div class="menu-container column is-9" style="overflow-y: scroll;">
                <section class="settings is-light">
                    <div id="my-profile" class="box">
                        <div class="box">
                            <h3 class="title is-4 has-text-black has-text-centered">Update my profile</h3>
                        </div>
                        <hr id="profile-pic" class="hr">
                        <h5 class="title is-5 has-text-black">Photo</h5>
                        <form id="profile-form" action="/settings/update-avatar" method="post" class="form"
                            enctype="multipart/form-data" accept="image/*" maxlength="2097152">
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
                                                    Choose a file...
                                                </span>
                                            </span>
                                            <span class="file-name">
                                                No file uploaded
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <button name="update" class="button is-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="notification">
                                        The recommended size is <strong>128x128</strong> pixels.
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr id="username" class="hr">
                        <h5 class="title is-5 has-text-black">Username</h5>
                        <form class="form" action="/settings/username" method="post">
                            <div class="columns is-vcentered">
                                <div class="column is-6">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="username" type="text" value="<?php if ($user)
                                                echo $user->getUsername() ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="field">
                                            <div class="control has-text-centered">
                                                <button class="button is-primary">Update</button>
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
                                                <input class="input" name="email" type="email" value="<?php if ($user)
                                                echo $user->getEmail() ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="field">
                                            <div class="control has-text-centered">
                                                <button class="button is-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr id="biography" class="hr">
                            <h5 class="title is-5 has-text-black">Biography</h5>
                            <form class="form" action="/settings/biography" method="post">
                                <div class="columns is-vcentered">
                                    <div class="column is-6">
                                        <div class="field">
                                            <div class="control">
                                                <textarea class="textarea" name="biography" type="text"><?php if ($user)
                                                echo $user->getBiography() ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="field">
                                            <div class="control has-text-centered">
                                                <button class="button is-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr id="delete" class="hr">
                            <h5 class="title is-5 has-text-black">Delete</h5>
                            <div class="columns is-vcentered">
                                <div class="column is-6">
                                    <div class="notification">
                                        Do you really wish to delete your account ?<br><br>
                                        Careful, this action is <strong class="has-text-danger">irreversible</strong>
                                        !<br>
                                        Once you account has been deleted, all your data will be deleted and you won't be
                                        able to retrieve it.
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                            <button id="delete-button" class="button is-danger">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="hr">
                            <div class="box">
                                <h3 id="security" class="title is-4 has-text-black has-text-centered">Safety</h3>
                            </div>
                            <hr id="update-password" class="hr">
                            <h5 class="title is-5 has-text-black">Update your password</h5>
                            <form class="form" action="/settings/update-password" method="post">
                                <div class="columns is-vcentered">
                                    <div class="column is-6">
                                        <div class="field">
                                            <div class="control">
                                                <div class="columns is-mobile">
                                                    <div class="column is-10">
                                                        <input class="input is-normal" type="password" name="password"
                                                            placeholder="Mot de passe actuel">
                                                    </div>
                                                    <div class="column is-1 has-text-centered">
                                                        <span class="icon is-small is-right toggle-password mt-3">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="control">
                                                <div class="columns is-mobile">
                                                    <div class="column is-10">
                                                        <input class="input is-normal" type="password" name="newPassword"
                                                            placeholder="Nouveau mot de passe">
                                                    </div>
                                                    <div class="column is-1 has-text-centered">
                                                        <span class="icon is-small is-right toggle-password mt-3">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="control">
                                                <div class="columns is-mobile">
                                                    <div class="column is-10">
                                                        <input class="input is-normal" type="password"
                                                            name="confirmPassword" placeholder="Confirmez le mot de passe">
                                                    </div>
                                                    <div class="column is-1 has-text-centered">
                                                        <span class="icon is-small is-right toggle-password mt-3">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3">
                                        <div class="field">
                                            <div class="control has-text-centered">
                                                <button class="button is-primary">Update</button>
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
                            <h5 class="title is-5 has-text-black">Recieve by email</h5>
                            <form class="form" action="/settings/update-notifications" method="post">
                                <div class="columns is-vcentered">
                                    <div class="column is-6">
                                        <div class="notification">Do you want to recieve notifications when you have a new
                                            comment by email ?</div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    <div class="field">
                                        <div class="control has-text-centered">
                                        <?php if ($user->getNotifs() == 0): ?>
                                            <input type="hidden" name="value" value="activated">
                                            <button class="button is-primary">Activate</button>
                                        <?php else: ?>
                                            <input type="hidden" name="value" value="deactivated">
                                            <button class="button is-danger">Deactivate</button>
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
                <h3 class="title is-4 is-centered">Delete your account</h3>
            </header>
            <section class="modal-card-body">
                <p class="has-text-centered">Do you really want to <strong class="has-text-danger">delete</strong>
                    your account permanently ?</p>
                <p class="has-text-centered">In that cas, please enter your password below.</p>
                <br>
                <div class="field">
                    <div class="control is-expanded">
                        <input class="input" name="password" type="password" placeholder="Mot de passe">
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot is-centered">
                <button class="button is-danger">Delete</button>
            </footer>
        </form>
    </div>
</div>
<?php
include_once('Utils/snackbar.php');
?>