<section class="forgotPassword">
    <div class="hero is-fullheight is-light">
        <div class="hero-body" style="height: 80vh; overflow: hidden;">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <div class="box">
                        <?php if (!isset($message)): ?>
                            <div class="box">
                                <img src="/Media/logo/logo.png" alt="Camagru">
                            </div>
                            <hr class="forgotPassword-hr">
                            <h3 class="title is-4 has-text-black">Mot de passe oublié</h3>
                            <hr class="forgotPassword-hr">
                            <div class="box">
                                <div class="title has-text-grey is-5">Veuillez entrer votre email. Nous vous enverrons un
                                    mail de récupération.
                                </div>
                                <form class="form" action="forgot-password" method="post">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input is-normal" type="email" name="email" placeholder="Email"
                                                autofocus="">
                                        </div>
                                    </div>
                                    <button class="button is-block is-info is-normal is-fullwidth">Envoyer</button>
                                </form>
                                <hr class="forgotPassword-hr">
                                <div class="title has-text-grey is-6">
                                    Vous n'avez pas de compte ?
                                    <a class="has-text-link" href="/register">Inscrivez-vous</a>
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
        </div>
</section>