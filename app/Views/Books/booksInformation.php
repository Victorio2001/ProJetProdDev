<?php

use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

$header_data = $header_data ?? [];
$cartBook = $cartBook ?? [];
$nextDatetoBeAvaible = $nextDatetoBeAvaible ?? [];
$userHasBook = $userHasBook ?? false;
$countnbInStock = $countnbInStock ?? 0;
$flash = Tools::getFlash();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information Livre</title>
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>
<body>
<?php View::render("Components", "navbar"); ?>

<?php View::render("Components", "image_avec_description", ['header_data' => $header_data]); ?>

<?php View::render("Components", "detailsBooks", [
    'cartBook' => $cartBook, 'nextDatetoBeAvaible' => $nextDatetoBeAvaible, 'userHasBook' => $userHasBook, 'countnbInStock' => $countnbInStock
]); ?>


<?php View::render("Components", "showFlashMessage", ['flash' => $flash]); ?>

<?php View::render("Components", "footer"); ?>
</body>
</html>
