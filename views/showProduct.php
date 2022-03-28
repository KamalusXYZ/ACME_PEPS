<?php

declare(strict_types=1);
require 'peps\core\Autoload.php';

use classes\Autoload;
use classes\Cfg;
use classes\DBAL;
use classes\Category;
use classes\Product;


try {
    Autoload::init();
    DBAL::init();
} catch (PDOException $e){
    exit("DB connection failed!");
} catch (Exception $e){
    exit ($e->getMessage());
}

// Récupérer l'idProduct reçu en GET.
$idProduct = filter_input(INPUT_GET, 'idProduct', FILTER_VALIDATE_INT);
//Si idProduct invalide rediriger vers vue 404.
if(!$idProduct || $idProduct < 0){
    header('Location:'.Cfg::APP_ERROR_404);
    exit;
}
// récupérer le produit depuis la base de donnée.
$product = new Product($idProduct);
if(!$product->hydrate()){
    header('Location:noProduct.php');
    exit;
}
// Formater le prix.
$product->PriceFormatted = NumberFormatter::create(Cfg::APP_LOCALE, NumberFormatter::PATTERN_DECIMAL, '#,##0.00'. Cfg::APP_CURRENCY)->format($product->price);





?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> <?= Cfg::APP_TITLE ?> </title>
	<link rel="stylesheet" href="assets/css/acme.css" />
</head>

<body>
	<header></header>
	<main>

		<div class="category">
			<a href="listProducts.php">Produits</a>  >  <?= $product->name ?>
		</div>
		<div id="detailProduct">
			<img src="<?= $product->getImgPath(Cfg::IMG_BIG) ?>" alt="<?= $product->name ?>" />
			<div>
				<div class="price"><?= $product->PriceFormatted ?></div>
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