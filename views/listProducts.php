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
    <?php
    foreach ($categories as $category) {
        ?>

        <div class="category">
            <a href="/product/create/<?= $category->idCategory ?>">
                <img class="ico" src="/assets/img/ico/create.svg" alt="Add a product in this category" />
            </a>
            <?= $category->name ?>
        </div>

        <?php
        foreach ($category->products as $product) {
            ?>

            <div class="blockProduct">
                <a href="/product/show/<?=$product->idProduct?>">
                    <img class="thumbnail" src="<?=$product->getImgPath('small')?>" alt="<?= $product->name ?>" />

                    <div class="name"> <?= $product->name ?> </div>

                </a>


            </div>
            <?php
        }
    }
    ?>
</main>
<footer></footer>
</body>