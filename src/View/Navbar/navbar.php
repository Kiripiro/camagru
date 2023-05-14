<nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="/">
            <img src="/Media/logo/logo.png">
        </a>

        <a role="button" id="navbar-search-button" class="navbar-search is-hidden-desktop" aria-label="search">
            <i class="fas fa-search"></i>
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbar-menu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>
    <div id="navbar-menu" class="navbar-menu">
        <div class="navbar-start">
            <a href="/gallery" class="navbar-item">
                <i class="icon is-flex is-centered is-medium fas fa-images"></i>
            </a>
            <?php if (isset($user)) { ?>
                <a href="/studio" class="navbar-item">
                    <i class="icon is-flex is-centered is-medium fas fa-camera-retro"></i>
                </a>
                <div class="navbar-item is-hidden-touch">
                    <div class="navbar-searchbar field has-addons">
                        <div class="control">
                            <input id="searchbar" name="input" class="input" type="text" placeholder="Rechercher..."
                                onkeypress="handleKeyPress(event)">
                        </div>
                        <div class="control">
                            <button class=" button is-hoverable" onclick="searchUser()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="navbar-end">
            <?php if (isset($user)) { ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        <?php if ($user->getAvatar() != null) { ?>
                            <div class="navbar-avatar"
                                style='background-image: url("/Media/avatars/<?php echo $user->getAvatar(); ?>"); background-size: cover; background-position: 50% 50%; border-radius: 100%; height: 28px; width: 28px; margin-right:10px;'>
                            <?php } else { ?>
                                <div class="navbar-avatar"
                                    style='background-image: url("/Media/avatars/avatar.png"); background-size: cover; border-radius: 100%; background-position: 50% 50%; height: 28px; width: 28px; margin-right:10px;'>
                                <?php } ?>
                            </div>
                            <?php echo $user->getLogin(); ?>
                    </a>
                    <div class="navbar-dropdown">
                        <a href="/profile" class="navbar-item">
                            Mon profil
                        </a>
                        <a href="/settings" class="navbar-item">
                            Paramètres
                        </a>
                        <hr class="navbar-divider">
                        <a href="/logout" class="navbar-item">
                            Déconnexion
                        </a>
                    </div>
                </div>
                <?php if ($user->getAdmin() == '1') { ?>
                    <div class="navbar-item">
                        <div class="buttons">
                            <a href="/admin" class="button is-primary">
                                <strong>Admin</strong>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            <? } else { ?>
                <div class="navbar-item">
                    <div class="buttons">
                        <a href="/login" class="button is-light">
                            Se connecter
                        </a>
                        <a href="/register" class="button is-primary">
                            <strong>Créer un compte</strong>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</nav>

<div id="search-modal" class="modal">
    <div id="navbar-modal-background" class="modal-background"></div>
    <div class="modal-card">
        <section id="navbar-modal-card-body" class="modal-card-body">
            <div class="field has-addons">
                <div class="control is-expanded">
                    <input id="searchbar-mobile" name="input" class="input" type="text" placeholder="Rechercher..."
                        onkeypress="handleKeyPress(event)">
                </div>
                <div class="control">
                    <button class=" button is-hoverable" onclick="searchUser()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>