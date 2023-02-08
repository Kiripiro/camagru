<nav class="navbar">
    <div class="nav-wrapper">
        <a href="/" class="logo">Camagru</a>
        <form class="search-box-form" action="/search" method="post">
            <input type="text" class="search-box" placeholder="Rechercher...">
        </form>
        <div class="nav-items">
            <?php if (isset($user)): ?>
                <a class="icon" href="/photo">
                    <i class="fa-solid fa-camera"></i>
                </a>
                <a class="icon" href="/notifications">
                    <i class="fa-solid fa-bell"></i>
                </a>
                <div class="dropdown">
                    <a class="user_profile">
                        <?php if ($user->getAvatar() == null): ?>
                            <div class="icon">
                                <i class="fa-solid fa-circle-user"></i>
                            </div>
                        <?php else: ?>
                            <img src="<?php echo $user->getAvatar(); ?>" alt="icon">
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-content">
                        <a href="/profile">
                            <i class="fa-solid fa-user"></i>
                            Profile
                        </a>
                        <a href="/settings">
                            <i class="fa-solid fa-cog"></i>
                            Paramètres
                        </a>
                        <a href="/logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Déconnection
                        </a>
                    </div>
                <?php else: ?>
                    <div class="not-logged-in">
                        <a class="login" href="/login">
                            Se connecter
                        </a>
                        <a class="register" href="/register">
                            Créer un compte
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</nav>