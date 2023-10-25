<?php
/**
 * @var \Odan\Session\Flash $flash
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />

    <title><?= $title ?? 'Welcome to Promo Exercise' ?></title>
<!--    <link rel="stylesheet" href="style.css" />-->
</head>
<body>
<div class="flash">
    <?php foreach ($flash->get('error') as $error): ?>
    <div class="error" style="color: red"><?= $error ?></div>
    <?php endforeach; ?>
</div>
<?= $content ?? '' ?>
</body>
</html>
