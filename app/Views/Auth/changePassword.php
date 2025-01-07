<?php

use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

$flash = Tools::getFlash();

if (isset($_SESSION["user"])) {
    header('Location: /accueil');
    exit();
}

$token = $token ?? '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php View::render("Components", "tailwind"); ?>
    <script src="/public/js/constraintPassword.js"></script>
</head>

<body>
<div class="flex flex-col md:flex-row h-screen">
    <div class="w-full lg:w-7/12 flex flex-col justify-center items-center">
        <div class="w-4/5 md:w-3/5 lg:w-2/5 xl:w-1/5">
            <a class="w-full">
                <img src="/public/img/logos/logo_w_texte.png" alt="Logo" class="w-3/5 mx-auto">
            </a>
        </div>
        <div class="max-w-6xl mt-10">
            <h1 class="text-primary-900 text-4xl font-bold">Réinitialisez votre mot de passe !</h1>
            <form action="/forgot-password/<?php echo $token ?>" method="post" class="w-full">
                <div class="mt-4">
                    <label for="mdp">Mot de passe :</label>
                    <input type="password" id="mdp" name="mdp" oninput="validatePassword()"
                           placeholder="Tapez votre nouveau mot de passe" required class="w-full border rounded p-2">
                </div>
                <div class="mt-4">
                    <label for="mdpConfirm">Confirmer mot de passe :</label>
                    <input type="password" id="mdpConfirm" name="mdpConfirm" oninput="validatePassword()"
                           placeholder="Confirmez votre nouveau mot de passe" required
                           class="w-full border rounded p-2">
                </div>
                <div id="passwordValidation" class="mt-4 w-full border rounded p-2">
                    <ul>
                        <li id="lengthCheck">❌ <span style="color: red;">Longueur minimale de 14 caractères</span></li>
                        <li id="uppercaseCheck">❌ <span style="color: red;">Au moins une majuscule</span></li>
                        <li id="lowercaseCheck">❌ <span style="color: red;">Au moins une minuscule</span></li>
                        <li id="specialCharCheck">❌ <span style="color: red;">Au moins un caractère spécial</span></li>
                        <li id="numberCheck">❌ <span style="color: red;">Au moins un chiffre</span></li>
                        <li id="matchCheck">❌ <span style="color: red;">Les mots de passe doivent être identiques</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-4 text-center">
                    <input type="submit" value="Valider" id="submitBtn"
                           class="bg-gray-200 text-white px-10 py-2 rounded-full cursor-pointer inline-block" disabled>
                </div>

            </form>
        </div>
    </div>
    <div class="w-full h-full hidden lg:block lg:w-5/12">
        <div class="w-full h-full flex flex-col justify-center items-center">
            <div class="relative w-full h-full">
                <img src="/public/img/login-right.jpg" alt="Logo"
                     class="w-full h-full object-cover object-center brightness-50">
                <div class="absolute top-0 left-0 right-0 bottom-0 flex flex-col justify-center items-center">
                    <h2 class="text-white text-2xl font-bold">"La lecture est une amitié."</h2>
                    <p class="text-white text-xl font-bold">− Marcel Proust</p>
                </div>
            </div>
        </div>
    </div>

    <?php View::render("Components", "showFlashMessage", ['flash' => $flash]); ?>

</div>
</body>

</html>
