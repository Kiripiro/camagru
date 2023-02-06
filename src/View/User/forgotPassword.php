<div class="wrapper">
    <div class="forgotPassword">
        <h1>Camagru</h1>
        <h2>Mot de passe oublié</h2>
        <form class="forgotPassword-form" action="forgot-password" method="post">
            <input type="email" name="email" placeholder="Email" />
            <input type="submit" value="Envoyer" />
        </form>
        <?php if (isset($message)): ?>
            <div class="message">
                <p>
                    <?= $message ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>