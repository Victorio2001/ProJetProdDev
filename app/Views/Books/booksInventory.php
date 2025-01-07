<?php

use BibliOlen\Tools\Http;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;
use BibliOlen\Core\Table;

$header_data = $header_data ?? [];
$currentPage = $currentPage ?? 1;
$pageAction = $pageAction ?? [];
$add = $add ?? [];
$totalPages = $totalPages ?? 1;
$bookTable = $bookTable ?? new Table(array(), array());
$params = Http::getUriParams();
$flash = Tools::getFlash();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire</title>
    <script src="/public/js/modal.js"></script>
    <link rel="stylesheet" href="/public/css/modal.css">
    <script src="/public/js/modalEditBook.js"></script>
    <script type="module" src="/public/js/filterTable.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>

<body>

<?php View::render("Components", "navbar"); ?>

<?php View::render("Components", "image_avec_description", ['header_data' => $header_data]); ?>
<?php View::render("Components", "showFlashMessage", ['flash' => $flash]); ?>

<?php $page = isset($params['option']) ? strtolower($params['option']) : 'inventaire'; ?>
<?php $links = ['inventaire' => 'Inventaire', 'transaction' => 'Transactions']; ?>

<nav class="pt-2">
    <ul class="flex justify-center mt-8">
        <?php foreach ($links as $key => $value) : ?>
            <li class="mr-8">
                <a href="?option=<?= $key ?>" class="w-full p-2 rounded-md mb-4 border border-blue-300 text-xl text-black
                <?= $page === $key ? ' text-blue-500' : ''; ?>">
                    <?= $value ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php if ($params['option'] == 'inventaire') : ?>
    <?php View::render("Components", "filterBooks", ['pageAction' => $pageAction, 'add' => $add]); ?>
<?php endif; ?>

<?php echo $bookTable->build(); ?>

<?php View::render("Components", "pagination", ['currentPage' => $currentPage, 'totalPages' => $totalPages]); ?>
<?php View::render("Components", "modal"); ?>
<?php View::render("Components", "footer"); ?>

</body>

</html>
