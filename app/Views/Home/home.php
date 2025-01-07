<?php

use BibliOlen\Tools\View;

$header_data = $header_data ?? [];
$cartBook = $cartBook ?? [];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>

<body>
<?php View::render("Components", "navbar"); ?>

<?php View::render("Components", "image_avec_description", ['header_data' => $header_data]); ?>
<h1 class="text-4xl font-bold text-center mt-4">Les derniers livres ajoutés</h1>

<?php if (empty($cartBook)) : ?>
    <p class="text-center text-gray-600 mt-4">Aucun livre n'a été ajouté pour le moment.</p>
<?php else : ?>
    <?php View::render("Components", "cartBook", ['cartBook' => $cartBook]); ?>
<?php endif; ?>

<?php View::render("Components", "footer"); ?>
</body>
</html>
