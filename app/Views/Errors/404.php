<?php use BibliOlen\Tools\View; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Non trouvée</title>
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>

<body>

<?php View::render("Components", "navbar"); ?>

<main class="flex justify-center items-center px-8 py-8 h-[68vh]">
    <div class="w-full md:w-2/3 lg:w-1/2 xl:w-1/3 bg-white shadow-md rounded-lg p-8">
        <h2 class="text-4xl text-gray-800 font-bold mb-6">
            404 - Page Non Trouvée
        </h2>
        <p class="text-gray-600 mb-4">
            Oops ! La page que vous recherchez n'existe pas.
        </p>
        <p class="text-gray-600 mb-8">
            Veuillez vérifier l'URL dans la barre d'adresse ou retourner à la page d'accueil.</p>
        <a href="/accueil" class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">
            Retour à la Page d'Accueil
        </a>
    </div>
</main>

<?php View::render("Components", "footer"); ?>

</body>
</html>
