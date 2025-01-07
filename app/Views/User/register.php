<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Popup</title>
    <script src="/public/js/modal.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/public/css/modal.css">
</head>

<body>
<button onclick="toggleModal()">Ouvrir la popup</button>

<div class="modalOverlay" id="modal-overlay">
    <div class="modal" id="modal-data">
        <div class="modal__header">
            <h2 class="modal__header__title">
                Inscription d’un utilisateur
            </h2>
            <button class="modal__header__close" aria-label="Close modal"  onclick="toggleModal()">
                <svg viewBox="0 0 20 20">
                    <path fill="#000000"
                          d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                </svg>
            </button>
        </div>

        <div class="modal__body">
            <div class="flex flex-col">
                <div class="w-full flex flex-col justify-center items-center">
                    <div class="max-w-6xl">
                        <form action="" method="post" class="w-full">
                            <div class="mt-4">
                                <label for="lastname">Nom :</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Votre nom" required
                                       class="w-full border rounded p-2">
                            </div>

                            <div class="mt-4">
                                <label for="firstname">Prénom :</label>
                                <input type="text" id="firstname" name="firstname" placeholder="Votre prénom" required
                                       class="w-full border rounded p-2">
                            </div>

                            <div class="mt-4">
                                <label for="email">Email :</label>
                                <input type="email" id="email" name="email" placeholder="email@lyon.ort.asso.fr" required
                                       class="w-full border rounded p-2">
                            </div>

                            <div class="mt-4">
                                <label for="password">Mot de passe :</label>
                                <input type="password" id="password" name="password" placeholder="********" required
                                       class="w-full border rounded p-2">
                            </div>

                            <div class="mt-4">
                                <label for="role">Rôle :</label>
                                <select id="role" name="role" required
                                        class="w-full border rounded p-2">
                                    <option value="apple">Étudiant</option>
                                    <option value="orange">Gestionnaire</option>
                                    <option value="banana">Professeur</option>
                                </select>
                            </div>

                            <div class="mt-4 text-center flex justify-center items-center">
                                <input type="submit" value="S’inscrire"
                                       class="bg-blue-500 text-white px-10 py-2 rounded-full cursor-pointer inline-block mr-1">
                                <input type="submit" value="Suppression "
                                       class="bg-red-500 text-white px-10 py-2 rounded-full cursor-pointer inline-block">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
