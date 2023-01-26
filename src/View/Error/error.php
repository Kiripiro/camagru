<div class="error">
    <h1>Une erreur s'est produite</h1>
    <div class="error-message">Message :
        <?= $exception->getMessage(); ?>
    </div>
    <div class="error-stack">Pile d'execution :
        <?= $exception->getTraceAsString(); ?>
    </div>
    <?php if (method_exists($exception, "getMoreDetail")) { ?>
        <div class="error-detail">
            <?= $exception->getMoreDetail(); ?>
        </div>
    <?php } ?>
</div>