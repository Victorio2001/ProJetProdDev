<?php

use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

$header_data = $header_data ?? [];
$dataTable = $dataTable ?? [];
$columnName = $columnName ?? [];
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$optionsPromotion = $optionsPromotion ?? [];
$optionsModule = $optionsModule ?? [];
$optionsRole = $optionsRole ?? [];
$flash = Tools::getFlash();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout Utilisateur</title>
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>
<body>
<?php View::render("Components", "navbar"); ?>

<?php View::render("Components", "image_avec_description", ['header_data' => $header_data]); ?>

<div class="flex items-center justify-center">
    <form method="post" id="myForm" action="/utilisateur/ajoutUtilisateur" enctype="multipart/form-data"
          class="max-w-5xl mt-4 w-full p-8 rounded shadow-md">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <div class="grid grid-cols-1 gap-4">
                    <div class="mb-4 relative">
                        <label for="nom_utilisateur" class="block">Nom</label>
                        <input type="text" id="nom_utilisateur" name="nom_utilisateur"
                               placeholder="Nom de l'utilisateur" class="w-full p-2 rounded-md border border-blue-300"
                               required>
                        <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                    </div>

                    <div class="mb-4 relative">
                        <label for="id_promotion" class="block">Promotion</label>
                        <select id="id_promotion" name="id_promotion"
                                class="w-full p-2 rounded-md border border-blue-300" required>
                            <?php foreach ($optionsPromotion as $option) : ?>
                                <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                    </div>

                    <div class="mb-4 relative">
                        <label for="module" class="block">Module :</label>
                        <select id="module" name="module[]" multiple
                                class="w-full p-2 rounded-md border border-blue-300" required>
                            <?php foreach ($optionsModule as $option) : ?>
                                <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                    </div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-1 gap-4">
                    <div class="mb-4 relative">
                        <label for="prenom_utilisateur" class="block">Prénom</label>
                        <input type="text" id="prenom_utilisateur" name="prenom_utilisateur"
                               placeholder="Prénom de l'utilisateur"
                               class="w-full p-2 rounded-md border border-blue-300" required>
                        <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                    </div>

                    <div class="mb-4 relative">
                        <label for="email_utilisateur" class="block">Email</label>
                        <input type="email" id="email_utilisateur" name="email_utilisateur" placeholder="Adresse e-mail"
                               pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Veuillez saisir une adresse e-mail valide."
                               class="w-full p-2 rounded-md border border-blue-300" required>
                        <p id="mailError" class="text-red-500 mt-2 hidden">Veuillez saisir une adresse e-mail
                            valide.</p>
                        <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                    </div>

                    <div class="mb-4 relative">
                        <label for="id_role" class="block">Role</label>
                        <select id="id_role" name="id_role" class="w-full p-2 rounded-md border border-blue-300"
                                required>
                            <?php foreach ($optionsRole as $option) : ?>
                                <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center">
            <button class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">Ajouter un
                utilisateur
            </button>
        </div>
    </form>

    <?php View::render("Components", "showFlashMessage", ['flash' => $flash]); ?>
</div>

<?php View::render("Components", "footer"); ?>

</body>
</html>
