<!doctype html>
<html lang="fr">

<head>
    <?= $cssContent ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= isset($title) ? $title : 'Default Title'; ?>
    </title>
    <link rel="icon" type="image/x-icon" href="/Media/logo/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <meta name="description" content="<?= isset($description) ? $description : 'Default Description'; ?>">
</head>

<body>
    <?= $content; ?>
    <?= $jsContent; ?>
    <script src="../Js/layout.js"></script>
</body>

</html>