<div class="navbar-spacer"></div>
<section class="profile">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-9">
                <div class="profile-container is-centered">
                    <section class="profile-info is-light">
                        <div class="box">
                            <div class="box">
                                <h3 class="title is-4 has-text-black has-text-centered">Mon profil</h3>
                            </div>
                            <div class="box">
                                <div class="columns">
                                    <div class="profile-avatar column is-3">
                                        <div class="profile-avatar-container">
                                            <div class="has-text-centered">
                                                <figure class="image is-128x128 is-inline-block">
                                                    <?php if ($user->getAvatar() != null): ?>
                                                        <img class="image is-rounded"
                                                            src="/Media/avatars/<?= $user->getAvatar() ?>" alt="Logo">
                                                    <?php else: ?>
                                                        <img class="image is-rounded" src="/Media/avatars/avatar.png"
                                                            alt="Logo">
                                                    <? endif; ?>
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-infos column is-9">
                                        <div class="profile-infos-container">
                                            <div class="columns">
                                                <div class="column is-3">
                                                    <label class="label">Login</label>
                                                    <p class="text is-6">
                                                        <?= $user->getLogin() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Prénom</label>
                                                    <p class="text is-6">
                                                        <?= $user->getFirstname() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Nom</label>
                                                    <p class="text is-6">
                                                        <?= $user->getLastname() ?>
                                                    </p>
                                                </div>
                                                <div class="column is-3">
                                                    <label class="label">Posts</label>
                                                    <p class="text is-6">
                                                        125
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="profile-infos-bis-container">
                                                <label class="label">Biographie</label>
                                                <p class="text is-6">
                                                    <?= $user->getBiography() ?>
                                                </p>
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
</section>