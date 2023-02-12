<div class="wrapper">
    <div class="settings">
        <div class="settings-header" onclick="toggleUpdateLogin()">
            <h2>Changer de login</h2>
            <i id="arrow" class="fas fa-chevron-down"></i>
            <div id="update-login" class="settings-body" style="display:none;">
                <form class="update-login-form" action="update-login" method="post">
                    <input type="text" name="login" placeholder="Login" />
                    <input type="submit" value="Changer" />
                </form>
                <div class="message">
                    <?php if (isset($message)): ?>
                        <p>
                            <?php echo $message; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="settings">
        <div class="settings-header update-password" onclick="toggleUpdatePassword()">
            <h2>Changer de mot de passe</h2>
            <i id="arrow" class="fas fa-chevron-down"></i>
            <div id="update-password" class="settings-body" style="display:none;">
                <form class="update-password-form" action="update-password" method="post">
                    <input type="password" name="oldPassword" placeholder="Ancien mot de passe" />
                    <input type="password" name="password" placeholder="Nouveau mot de passe" />
                    <input type="password" name="confirmPassword" placeholder="Confirmer le mot de passe" />
                    <input type="submit" value="Changer" />
                </form>
                <div class="message">
                    <?php if (isset($message)): ?>
                        <p>
                            <?php echo $message; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>