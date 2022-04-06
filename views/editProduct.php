<?php

declare(strict_types=1);

use peps\core\Cfg;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title><?= Cfg::get('appTitle') ?></title>
	<link rel="stylesheet" href="/assets/css/acme.css">
</head>

<body>
	<header></header>
	<main>
		<div class="category">
			<a href="/product/list">Produits</a> &gt; Editer
		</div>
		<div class="error">XXX</div>
		<form name="form1" action="XXX" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="idProduct" value="XXX" />
			<div class="item">
				<label>Catégorie</label>
				<select name="idCategory">
				</select>
			</div>
			<div class="item">
				<label>Nom</label>
				<input name="name" value="XXX" size="20" maxlength="50" required="required" />
			</div>
			<div class="item">
				<label>Référence</label>
				<input name="ref" value="XXX" size="10" maxlength="10" required="required" />
			</div>
			<div class="item">
				<label>Prix</label>
				<input type="number" name="price" value="XXX" min="0.01" max="9999.99" step="0.01" size="7" maxlength="7" required="required" />
			</div>
			<div class="item">
				<label></label>
				<a href="XXX"><input type="button" value="Annuler" /></a>
				<input type="submit" name="submit" value="Valider" />
			</div>
		</form>
	</main>
	<footer></footer>
</body>

</html>