<!doctype html>
<html lang="en">

<head>
    <?= $cssContent ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= isset($title) ? $title : 'Default Title'; ?>
    </title>
    <link rel="icon" type="image/x-icon" href="/Media/logo/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css" />
    <link rel="stylesheet" href="/Css/style.css">
    <meta name="description" content="<?= isset($description) ? $description : 'Default Description'; ?>">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>

<body>
    <script src="../Js/snackbar.js"></script>
    <div class="wrapper">
        <?= $content; ?>
    </div>
    <?= $jsContent; ?>
    <script src="../Js/layout.js"></script>
</body>

</html>
