<?php

use BibliOlen\Tools\Tools;
use BibliOlen\Tools\View;

$header_data = $header_data ?? [];
$flash = Tools::getFlash();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout livre</title>
    <link rel="stylesheet" href="/public/css/modal.css">
    <script src="/public/js/modal.js"></script>
    <?php View::render("Components", "tailwind"); ?>
</head>

<body>
<?php View::render("Components", "navbar"); ?>

<?php View::render("Components", "image_avec_description", ['header_data' => $header_data]); ?>

<div class="flex items-center justify-center max-w-6xl mx-auto mt-4 w-full p-8 rounded">
    <div>
        <form method="post" id="myForm" action="/inventaire/ajoutLivreInventaire" enctype="multipart/form-data">
            <div class="mb-4 relative">
                <label for="titre_livre" class="block">Titre :</label>
                <input type="text" id="titre_livre" name="titre_livre" placeholder="Titre du livre"
                       class="w-full p-2 rounded-md border border-blue-300" required>
                <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="mb-4 relative">
                            <label for="nombre_exemplaires" class="block">Nombre d'exemplaires :</label>
                            <input type="number" id="nombre_exemplaires" name="nombre_exemplaires"
                                   placeholder="Nombre d'exemplaires du livre"
                                   class="w-full p-2 rounded-md border border-blue-300" required min="0">
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>

                        <div class="mb-4 relative">
                            <label for="image" class="block">Image de couverture ( formats : .png, .jpg, .jpeg )
                                :</label>
                            <input type="file" id="image" name="image"
                                   class="w-full p-2 rounded-md border border-blue-300 cursor-pointer text-gray-400 border-blue-300 placeholder-gray-400"
                                   accept=".png, .jpg, .jpeg" required>
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>

                        <div class="mb-4 relative">
                            <label for="resume_livre" class="block">Résumé :</label>
                            <input type="text" id="resume_livre" name="resume_livre" placeholder="Résumé du livre"
                                   class="w-full p-2 rounded-md border border-blue-300" required>
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>

                        <div class="mb-4 relative">
                            <label for="id_mot_cle" class="block">Mot clés :</label>
                            <select id="id_mot_cle" name="id_mot_cle[]" multiple
                                    class="w-full p-2 rounded-md border border-blue-300" required>
                            </select>
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="mb-4 relative">
                            <label for="isbn" class="block">ISBN :</label>
                            <input type="number" id="isbn" name="isbn" placeholder="ISBN du livre"
                                   class="w-full p-2 rounded-md border border-blue-300" required>
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>

                        <div class="mb-5 relative">
                            <label for="annee_publication" class="block">Année de parution :</label>
                            <input type="number" id="annee_publication" name="annee_publication" min="1900"
                                   max="<?php echo date('Y'); ?>" placeholder="Année de parution du livre"
                                   class="w-full p-2 rounded-md border border-blue-300" required>
                            <p id="yearError" class="text-red-500 mt-2 hidden">Veuillez saisir une année valide entre
                                1900 et l'année actuelle.</p>
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>

                        <div class="mb-5 relative">
                            <label for="id_editeur" class="block">Editeur :</label>
                            <select id="id_editeur" name="id_editeur"
                                    class="w-full p-2 rounded-md border border-blue-300" required>
                            </select>
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>

                        <div class="mb-4 relative">
                            <label for="id_auteur" class="block">Choix de l'auteur :</label>
                            <select id="id_auteur" name="id_auteur[]" multiple
                                    class="w-full p-2 rounded-md border border-blue-300" required></select>
                            <span class="absolute right-0 top-0 mt-1 mr-2 text-red-600">*</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <button class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">Ajouter le livre
                </button>
            </div>
        </form>
        <div class="flex justify-center gap-6 my-5">
            <button class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700"
                    id="open_modal_keyword">
                Ajout de Mot clé
            </button>
            <button id="open_modal_publisher"
                    class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">
                Ajout Editeur
            </button>
            <button id="open_modal_author"
                    class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">
                Ajout d'auteurs
            </button>
        </div>

        <?php View::render("Components", "modal"); ?>
    </div>

    <?php View::render("Components", "showFlashMessage", ['flash' => $flash]); ?>

</div>

<?php View::render("Components", "footer"); ?>

</body>

<script>
    async function getAllKeyword() {
        return new Promise((resolve, _) => {
            fetch('/api/keywords', {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    const keywordList = document.getElementById('id_mot_cle');

                    data.forEach(keyword => {
                        const keywordElement = document.createElement('option');
                        keywordElement.textContent = keyword.id + ' - ' + keyword.name;
                        keywordList.appendChild(keywordElement);
                    });

                    resolve();
                });
        });
    }

    async function getAllPublisher() {
        return new Promise((resolve, _) => {
            fetch('/api/publishers', {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    const publisherList = document.getElementById('id_editeur');

                    data.forEach(publisher => {
                        const publisherElement = document.createElement('option');
                        publisherElement.textContent = publisher.id + ' - ' + publisher.name;
                        publisherList.appendChild(publisherElement);
                    });

                    resolve();
                });
        });
    }

    async function getAllAuthor() {
        return new Promise((resolve, _) => {
            fetch('/api/authors', {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    const authorList = document.getElementById('id_auteur');

                    data.forEach(author => {
                        const authorElement = document.createElement('option');
                        authorElement.textContent = author.id + ' - ' + author['firstName'] + " " + author['lastName'];
                        authorList.appendChild(authorElement);
                    });

                    resolve();
                });
        });
    }
</script>

<script>

    getAllKeyword().then(() => {
        getAllPublisher().then(() => {
            getAllAuthor();
        });
    });

    const openModalKeywordBtn = document.getElementById('open_modal_keyword');
    const openModalPublisherBtn = document.getElementById('open_modal_publisher');
    const openModalAuthorBtn = document.getElementById('open_modal_author');
    const modal = document.getElementById('modal-overlay');

    openModalKeywordBtn.addEventListener('click', () => {
        const modalFormDiv = document.getElementById('modal_form_div');
        modalFormDiv.innerText = '';

        const modalTitle = document.getElementById('modal_title');
        modalTitle.textContent = "Ajout Mot clé";

        const form = document.createElement('form');
        form.id = "keywordForm";
        form.action = "";

        const div = document.createElement('div');
        div.classList.add('mb-4', 'relative');

        const label = document.createElement('label');
        label.for = "keyword";
        label.classList.add('block');
        label.textContent = "Nom du mot clé :";

        const input = document.createElement('input');
        input.type = "text";
        input.id = "keyword";
        input.name = "keyword";
        input.placeholder = "Mot clé a ajouter";
        input.classList.add('p-2', 'rounded-md', 'border', 'border-blue-300');

        const span = document.createElement('span');
        span.classList.add('absolute', 'right-0', 'top-0', 'mt-1', 'mr-2', 'text-red-600');
        span.textContent = "*";

        div.appendChild(label);
        div.appendChild(input);
        div.appendChild(span);

        form.appendChild(div);

        const responseDiv = document.createElement('div');
        responseDiv.id = "responseDiv";
        form.appendChild(responseDiv);


        const button = document.createElement('button');
        button.classList.add('mt-4', 'px-4', 'py-2', 'bg-primary-900', 'text-white', 'rounded-2xl', 'hover:bg-primary-700');
        button.textContent = "Ajouter mot clé";

        form.appendChild(button);

        form.onsubmit = (e) => {
            e.preventDefault();

            const keyword = document.getElementById('keyword').value;

            fetch('/api/keyword', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({keyword})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const responseDiv = document.getElementById('responseDiv');
                        responseDiv.classList.add('text-green-500');
                        responseDiv.textContent = "Mot clé ajouté avec succès";

                        getAllKeyword();

                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-red-500');
                    responseDiv.textContent = "Erreur lors de l'ajout du mot clé";

                });
        };


        modalFormDiv.appendChild(form);

        toggleModal();
    });

    openModalPublisherBtn.addEventListener('click', () => {

        const modalFormDiv = document.getElementById('modal_form_div');
        modalFormDiv.innerText = '';

        const modalTitle = document.getElementById('modal_title');
        modalTitle.textContent = "Ajouter un éditeur";

        const form = document.createElement('form');
        form.id = "publisherForm";
        form.action = "";

        const div = document.createElement('div');
        div.classList.add('mb-4', 'relative');

        const label = document.createElement('label');
        label.for = "publisher";
        label.classList.add('block');
        label.textContent = "Nom de l'editeur :";

        const input = document.createElement('input');
        input.type = "text";
        input.id = "publisher";
        input.name = "publisher";
        input.placeholder = "Nom de l'éditeur";
        input.classList.add('p-2', 'rounded-md', 'border', 'border-blue-300');

        const span = document.createElement('span');
        span.classList.add('absolute', 'right-0', 'top-0', 'mt-1', 'mr-2', 'text-red-600');
        span.textContent = "*";

        div.appendChild(label);
        div.appendChild(input);
        div.appendChild(span);

        form.appendChild(div);

        const responseDiv = document.createElement('div');
        responseDiv.id = "responseDiv";
        form.appendChild(responseDiv);


        const button = document.createElement('button');
        button.classList.add('mt-4', 'px-4', 'py-2', 'bg-primary-900', 'text-white', 'rounded-2xl', 'hover:bg-primary-700');
        button.textContent = "Ajouter l'éditeur";

        form.appendChild(button);

        form.onsubmit = (e) => {
            e.preventDefault();

            const publisher = document.getElementById('publisher').value;

            fetch('/api/publisher', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({publisher})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const responseDiv = document.getElementById('responseDiv');
                        responseDiv.classList.add('text-green-500');
                        responseDiv.textContent = "Editeur ajouté avec succès";

                        getAllPublisher();

                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-red-500');
                    responseDiv.textContent = "Erreur lors de l'ajout de l'editeur";

                });
        };

        modalFormDiv.appendChild(form);

        toggleModal();
    });

    openModalAuthorBtn.addEventListener('click', () => {

        const modalFormDiv = document.getElementById('modal_form_div');
        modalFormDiv.innerText = '';

        const modalTitle = document.getElementById('modal_title');
        modalTitle.textContent = "Ajouter un auteur";

        const form = document.createElement('form');
        form.id = "authorForm";
        form.action = "";

        const div = document.createElement('div');
        div.classList.add('mb-4', 'relative');

        const label = document.createElement('label');
        label.for = "name_author";
        label.classList.add('block');
        label.textContent = "Nom de l'auteur :";

        const input = document.createElement('input');
        input.type = "text";
        input.id = "name_author";
        input.name = "name_author";
        input.placeholder = "Nom de l'auteur ou Pseudonyme";
        input.classList.add('p-2', 'rounded-md', 'border', 'border-blue-300');

        const label2 = document.createElement('label');
        label2.for = "firstname_author";
        label2.classList.add('block','mt-4');
        label2.textContent = "Prénom de l'auteur :";

        const input2 = document.createElement('input');
        input2.type = "text";
        input2.id = "firstname_author";
        input2.name = "firstname_author";
        input2.placeholder = "Prénom de l'auteur";
        input2.classList.add('p-2', 'rounded-md', 'border', 'border-blue-300');

        const span = document.createElement('span');
        span.classList.add('absolute', 'right-0', 'top-0', 'mt-1', 'mr-2', 'text-red-600');
        span.textContent = "*";

        div.appendChild(label);
        div.appendChild(input);
        div.appendChild(label2);
        div.appendChild(input2);
        div.appendChild(span);

        form.appendChild(div);

        const responseDiv = document.createElement('div');
        responseDiv.id = "responseDiv";
        form.appendChild(responseDiv);

        const button = document.createElement('button');
        button.classList.add('mt-4', 'px-4', 'py-2', 'bg-primary-900', 'text-white', 'rounded-2xl', 'hover:bg-primary-700');
        button.textContent = "Ajouter l'auteur";

        form.appendChild(button);

        form.onsubmit = (e) => {
            e.preventDefault();

            const name_author = document.getElementById('name_author').value;
            const firstname_author = document.getElementById('firstname_author').value;

            const requestData = {lastname: name_author};

            if (firstname_author.trim() !== '') {
                requestData.firstname = firstname_author;
            }

            fetch('/api/author', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const responseDiv = document.getElementById('responseDiv');
                        responseDiv.classList.add('text-green-500');
                        responseDiv.textContent = "Auteur ajouté avec succès";

                        getAllAuthor();

                    }
                })
                .catch(_ => {
                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-red-500');
                    responseDiv.textContent = "Erreur lors de l'ajout de l'auteur";

                });
        };


        modalFormDiv.appendChild(form);

        toggleModal();
    });

</script>
</html>
