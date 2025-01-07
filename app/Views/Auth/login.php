<?php

use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

try {
    $csrfToken = bin2hex(random_bytes(32));
} catch (Exception $e) {
    $csrfToken = bin2hex(Tools::generateRandomString(128));
}

$_SESSION['token'] = $csrfToken;

$flash = Tools::getFlash();

if (isset($_SESSION["user"])) {
    header('Location: /accueil');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php View::render("Components", "tailwind"); ?>
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
            <h1 class="text-primary-900 text-4xl font-bold">Connectez-vous à 1001 librairies !</h1>
            <form action="/login" method="post" class="w-full">
                <input type="hidden" name="token" value="<?php echo $csrfToken; ?>">
                <div class="mt-4">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" placeholder="vous@exemple.fr" required
                           class="w-full border rounded p-2">
                </div>

                <div class="mt-4">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" placeholder="************" required
                           class="w-full border rounded p-2">
                </div>

                <div class="flex items-center mt-4">
                    <input type="checkbox" id="rememberMe" name="rememberMe" class="mr-2">
                    <label for="rememberMe">Se souvenir de moi</label>
                    <a href="/forgot-password" class="ml-auto text-primary-700 hover:underline hover:text-primary-900">Mot de passe
                        oublié ?</a>
                </div>

                <div class="mt-4 text-center">
                    <input type="submit" value="Se connecter"
                           class="bg-primary-900 text-white px-10 py-2 rounded-full cursor-pointer inline-block">
                </div>
            </form>

            <hr class="mt-5 mb-5 border border-gray-300">

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
