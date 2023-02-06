<div class="wrapper">
    <div class="register">
        <h1>Camagru</h1>
        <h2>Créer un compte</h2>
        <form class="register-form" action="register" method="post">
            <input type="text" name="firstname" placeholder="Prénom" />
            <input type="text" name="lastname" placeholder="Nom" />
            <input type="text" name="login" placeholder="Login" />
            <input type="text" name="email" placeholder="Email" />
            <input type="password" name="password" placeholder="Mot de passe" />
            <input type="password" name="confirmPassword" placeholder="Confirmez votre mot de passe" />
            <input type="submit" placeholder="register" value="S'inscrire" />
        </form>
        <?php if (isset($message)): ?>
            <div class="message">
                <p>
                    <?= $message ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <div class="already-registered">
        <p class="message">Vous avez un compte ? <a href="login">Connectez-vous</a></p>
    </div>
</div>