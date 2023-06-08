<section class="register">
    <div class="hero is-fullheight is-light">
        <div class="hero-body" style="height: 80vh; overflow: hidden;">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <div class="box">
                        <?php if (!isset($message)): ?>
                            <div class="box">
                                <img src="/Media/logo/logo.png" alt="Camagru">
                            </div>
                            <hr class="register-hr">
                            <h3 class="title is-4 has-text-black">Créer un compte</h3>
                            <hr class="register-hr">
                            <div class="box">
                                <div class="title has-text-grey is-5">Veuillez entrer votre username et votre mot de passe.
                                </div>
                                <form class="form" action="register" method="post">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input is-normal" type="text" name="firstname" placeholder="Prénom"
                                                autofocus="">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="control">
                                            <input class="input is-normal" type="text" name="lastname" placeholder="Nom"
                                                autofocus="">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="control">
                                            <input class="input is-normal" type="text" name="username"
                                                placeholder="Username" autofocus="">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="control">
                                            <input class="input is-normal" type="email" name="email" placeholder="Email"
                                                autofocus="">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="control">
                                            <div class="columns is-mobile">
                                                <div class="column is-10">
                                                    <input class="input is-normal" type="password" name="password"
                                                        placeholder="Mot de passe">
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
                                                    <input class="input is-normal" type="password" name="confirmPassword"
                                                        placeholder="Confirmez le mot de passe">
                                                </div>
                                                <div class="column is-1 has-text-centered">
                                                    <span class="icon is-small is-right toggle-password mt-3">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="button is-block is-info is-normal is-fullwidth">Se connecter</button>
                                </form>
                                <hr class="register-hr">
                                <div class="title has-text-grey is-6">
                                    Vous avez un compte ?
                                    <a class="has-text-link" href="/login">Connectez-vous</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="title has-text-grey is-6">
                                <img src="/Media/logo/logo.png" alt="Camagru">
                                <hr class="register-hr">
                                <div class="lds-grid">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="box">
                                    <?php echo $message; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</section>