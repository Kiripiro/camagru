<section class="home-section">
    <div class="home">
        <h1>Hello</h1>
        <?php
        if (isset($user)): ?>
            <span> Hello
                <?= $user->getFirstname(); ?>
            </span>
        <?php endif ?>
    </div>
</section>