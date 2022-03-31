<?php

declare(strict_types=1);
use peps\core\Cfg;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Cfg::get('appTilte') ?></title>
    <link rel="stylesheet" href="/assets/css/acme.css"/>
</head>

<body>
<header></header>
<main>
    <div class="category">
        <a href="/">Accueil</a> &gt; Oups!
    </div>
    <img src="/assets/img/error404.png" alt="Oups !"/>
</main>
<footer></footer>
</body>

</html>