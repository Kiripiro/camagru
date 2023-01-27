<!doctype html>
<html lang="fr">

<head>
    <?= $cssContent ?>
    <meta charset="utf-8">
    <title>
        <?= isset($title) ? $title : 'Default Title'; ?>
    </title>
    <meta name="description" content="<?= isset($description) ? $description : 'Default Description'; ?>">
</head>

<body>
    <?= $content; ?>
    <?= $jsContent; ?>
</body>

</html>