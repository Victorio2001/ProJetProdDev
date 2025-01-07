<?php

use BibliOlen\Core\Table;
use BibliOlen\Tools\View;

$UserTable = $UserTable ?? new Table(array(), array());
$header_data = $header_data ?? [];
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
    <title>Utilisateur</title>
    <script src="/public/js/modal.js"></script>
    <script src="/public/js/modalEditUser.js"></script>
    <link rel="stylesheet" href="/public/css/modal.css">
    <script type="module" src="/public/js/filterTable.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>
<body>
<?php View::render("Components", "navbar");

 View::render("Components", "image_avec_description", ['header_data' => $header_data]);

 View::render("Components", "filterBooks", ['pageAction' => $pageAction, 'add' => $add]);

echo $UserTable->build();

 View::render("Components", "pagination", ['currentPage' => $currentPage, 'totalPages' => $totalPages]);

 View::render("Components", "modal");

 View::render("Components", "footer"); ?>

</body>
</html>
