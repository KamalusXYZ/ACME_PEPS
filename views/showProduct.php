<?php

declare(strict_types=1);
use peps\core\Cfg;

//Pour l IDE
$product = $product ?? null;
$categories = $categories ?? null;

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
        <a href="/product/list">Produits</a>  >  <?= $product->name  ?>
    </div>
    <div id="detailProduct">
        <img src="<?= $product->getImgPath('big') ?>" alt="<?= $product->name ?>" />
        <div>
            <div class="price"><?= $product->formattedPrice ?></div>
            <div class="category">catégorie<br />
                <?= $product->category->name ?>
            </div>
            <div class="ref">référence<br />
                <?= $product->ref ?></div>


        </div>
    </div>

</main>
<footer></footer>
</body>

</html>