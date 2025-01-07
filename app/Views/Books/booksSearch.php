<?php

use BibliOlen\Tools\View;

$header_data = $header_data ?? [];
$cartBook = $cartBook ?? [];
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$pageAction = $pageAction ?? [];
$add = $add ?? null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Livres</title>
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>

<body>
<?php View::render("Components", "navbar"); ?>

<?php View::render("Components", "image_avec_description", ['header_data' => $header_data]); ?>

<?php View::render("Components", "filterBooks", ['pageAction' => $pageAction, 'add' => $add]); ?>

<?php View::render("Components", "cartBook", ['cartBook' => $cartBook]); ?>

<?php View::render("Components", "pagination", ['currentPage' => $currentPage, 'totalPages' => $totalPages]); ?>

<?php View::render("Components", "footer"); ?>
</body>
</html>
