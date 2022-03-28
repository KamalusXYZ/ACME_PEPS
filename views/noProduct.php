<?php

declare(strict_types=1);

require '..\peps\core\Autoload.php';

use classes\Autoload;
use classes\Cfg;

try {
    Autoload::init();
} catch (Exception $e) {
    exit($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= Cfg::APP_TITLE ?></title>
	<link rel="stylesheet" href="assets/css/acme.css" />
</head>

<body>
	<header></header>
	<main>
		<div class="category">
			<a href="listProducts.php">Accueil</a> &gt; Produit indisponible
		</div>
		<img src="assets/img/noProduct.jpg" alt="Produit indisponible" />
	</main>
	<footer></footer>
</body>

</html>