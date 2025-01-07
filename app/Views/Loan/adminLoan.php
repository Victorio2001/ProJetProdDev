<?php

use BibliOlen\Core\Table;
use BibliOlen\Tools\View;

$header_data = $header_data ?? [];
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$pageAction = $pageAction ?? 1;
$hidden = $hidden ?? [];
$loanTable = $loanTable ?? new Table(array(), array());

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations</title>
    <script src="/public/js/modal.js"></script>
    <link rel="stylesheet" href="/public/css/modal.css">
    <script src="/public/js/modalEditLoan.js"></script>
    <script type="module" src="/public/js/filterTable.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>

<body>

<?php
View::render("Components", "navbar");

View::render("Components", "image_avec_description", ['header_data' => $header_data]);

View::render("Components", "filterBooks", ['pageAction' => $pageAction]);

echo $loanTable->build();

View::render("Components", "pagination", ['currentPage' => $currentPage, 'totalPages' => $totalPages]);
View::render("Components", "modal");

View::render("Components", "footer");
?>




</body>

</html>
