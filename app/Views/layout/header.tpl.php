<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $_SERVER['BASE_URI'] ?>/">
    <title>Mon site en MCV</title>
</head>
<body>

    <header>
        <h1>Mon site en MVC !</h1>
        <?php
            // On inclut une sous-vue => "partials"
            include __DIR__.'/../partials/nav.tpl.php';
        ?>
    </header>

    <main>