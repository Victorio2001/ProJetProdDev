<?php

use BibliOlen\Tools\View;

$header_data = $header_data ?? [];
$timelineItems = $timelineItems ?? [];
$flash = $flash ?? null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>

<body>

<?php
View::render("Components", "navbar");
View::render("Components", "image_avec_description", ['header_data' => $header_data]);
View::render("Components", "timeline", ['timelineItems' => $timelineItems]);
View::render("Components", "showFlashMessage", ['flash' => $flash]);
View::render("Components", "footer"); ?>
</body>

</html>
