<?php

use BibliOlen\Core\Table;
use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

$flash = Tools::getFlash();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profile</title>
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>
<body>
<?php View::render("Components", "navbar");
?>

<div class="flex flex-col md:flex-row h-screen">
    <div class="w-full lg:w-7/12 flex flex-col justify-center items-center">
        <div class="max-w-6xl mt-10">
            <h1 class="text-primary-900 text-4xl font-bold flex flex-col justify-center items-center mb-10">Mon profil !</h1>
            <form action="/profile" method="post" class="w-full">
                <div class="mt-4 text-center">
                    <label for="nom">Nom :</label>
                    <span id="nom" class="p-2"><?php echo $_SESSION["user"]->firstName ?></span>
                </div>

                <div class="mt-4 text-center">
                    <label for="prenom">Prénom :</label>
                    <span id="prenom" class="p-2"><?php echo $_SESSION["user"]->lastName ?></span>
                </div>

                <div class="mt-4 text-center">
                    <label for="email">Email :</label>
                    <span id="email" class="p-2"><?php echo $_SESSION["user"]->email ?></span>
                </div>

                <div class="mt-4 text-center mb-5">
                    <label for="promo">Classe :</label>
                    <span id="promo" class="p-2"><?php echo $_SESSION["user"]->promotion->name ?></span>
                </div>

                <div class="mt-4 text-center mb-10">
                    <input type="submit" value="Modifier votre mot de passe" class="bg-primary-900 text-white px-10 py-2 rounded-full cursor-pointer inline-block">
                </div>
            </form>

            <?php View::render("Components", "footer"); ?>
        </div>
    </div>
    <div class="w-full h-full hidden lg:block lg:w-5/12">
        <div class="w-full h-full flex flex-col justify-center items-center">
            <div class="relative w-full h-full">
                <img src="/public/img/monProfil.jpg" alt="Logo"
                     class="w-full h-full object-cover object-center brightness-50">
                <div class="absolute top-0 left-0 right-0 bottom-0 flex flex-col justify-center items-center">
                    <h2 class="text-white text-2xl font-bold">"Un livre est une fenêtre par laquelle on s'évade."</h2>
                    <p class="text-white text-xl font-bold">− Julien Green</p>
                </div>
            </div>
        </div>
    </div>

    <?php View::render("Components", "showFlashMessage", ['flash' => $flash]); ?>

</div>

</body>
</html>
