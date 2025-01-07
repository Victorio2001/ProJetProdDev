const modal = document.getElementById('modal-overlay');


function openBookModal(e) {
    const modalFormDiv = document.getElementById('modal_form_div');
    modalFormDiv.innerText = '';

    const modalTitle = document.getElementById('modal_title');
    modalTitle.textContent = "Modifier un livre";

    const form = document.createElement('form');
    form.id = "bookForm";
    form.action = "";

    const btnContext = e.getAttribute("data-context").split(';');

    const idBook = document.createElement('input');
    idBook.id = "id_book"
    idBook.name = "id_book"
    idBook.type = "hidden"
    idBook.value = btnContext[0].trim();

    form.appendChild(idBook);

    const div = document.createElement('div');
    div.classList.add('mb-4', 'relative');

    const label = document.createElement('label');
    label.for = "book";
    label.classList.add('block');
    label.textContent = "Nombre d'exemplaire :"

    const labeltitre = document.createElement('label');
    labeltitre.for = "titre";
    labeltitre.classList.add('block');
    labeltitre.textContent = "Titre du livre :";

    const labelresume = document.createElement('label');
    labelresume.for = "resume";
    labelresume.classList.add('block');
    labelresume.textContent = "Résumé du livre :";

    const labeldate = document.createElement('label');
    labeldate.for = "date";
    labeldate.classList.add('block');
    labeldate.textContent = "Date de publication du livre :";

    const name = document.createElement('input');
    name.type = "text";
    name.id = "name";
    name.name = "name";
    name.placeholder = "Titre du Livre";
    name.classList.add('p-2', 'rounded-md', 'border', 'w-96', 'border-blue-300');
    name.required = true;
    name.defaultValue = btnContext[2].trim()

    const resume = document.createElement('input');
    resume.type = "text";
    resume.id = "resume";
    resume.name = "resume";
    resume.placeholder = "Résumé du Livre";
    resume.classList.add('p-2', 'rounded-md', 'w-96', 'border', 'border-blue-300');
    resume.required = true;
    resume.defaultValue = btnContext[3].trim()

    const date = document.createElement('input');
    date.type = "number";
    date.id = "date";
    date.name = "date";
    date.placeholder = "Date de publication du Livre";
    date.classList.add('p-2', 'rounded-md', 'w-96', 'border', 'border-blue-300');
    date.required = true;
    date.defaultValue = btnContext[4].trim()
    date.min = "1900";

    const input = document.createElement('input');
    input.type = "number";
    input.id = "book";
    input.name = "book";
    input.placeholder = "Nouveau nombre d'exemplaire";
    input.classList.add('p-2', 'rounded-md', 'border', 'w-96', 'border-blue-300');
    input.required = true;
    input.defaultValue = btnContext[5].trim()
    input.min = btnContext[1].trim() === "null" ? "0" : btnContext[1].trim();

    const span = document.createElement('span');
    span.classList.add('absolute', 'right-0', 'top-0', 'mt-1', 'mr-2', 'text-red-600');
    span.textContent = "*";

    div.appendChild(label);
    div.appendChild(input);

    div.appendChild(labeltitre);
    div.appendChild(name);

    div.appendChild(labelresume);
    div.appendChild(resume);

    div.appendChild(labeldate);
    div.appendChild(date);

    div.appendChild(span);
    form.appendChild(div);

    const responseDiv = document.createElement('div');
    responseDiv.id = "responseDiv";


    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('flex', 'justify-center');

    const button = document.createElement('button');
    button.classList.add('mt-4', 'px-4', 'py-2', 'bg-primary-900', 'text-white', 'rounded-lg', 'hover:bg-primary-700');
    button.textContent = "Modifier le livre";

    buttonContainer.appendChild(button);

    form.appendChild(buttonContainer);
    form.appendChild(responseDiv);

    form.onsubmit = (e) => {
        e.preventDefault();


        const id_book = document.getElementById('id_book').value;
        const nombre_exemplaires = document.getElementById('book').value;
        const resume = document.getElementById('resume').value;
        const date = document.getElementById('date').value;
        const name = document.getElementById('name').value;
        let add = 0;
        let supp = 0;

        const result = nombre_exemplaires - btnContext[5].trim();
        if (result < 0) {
            supp = result * -1;
        } else {
            add = result;
        }

        fetch('/api/book', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({id_book, nombre_exemplaires, date, resume, name, add, supp})
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-green-500');
                    responseDiv.textContent = "Livre modifié avec succès";
                    location.reload();

                }
            })
            .catch(error => {
                console.error('Error:', error);

                const responseDiv = document.getElementById('responseDiv');
                responseDiv.classList.add('text-red-500');
                responseDiv.textContent = "Erreur lors de la modification du livre";
            });
    };

    modalFormDiv.appendChild(form);

    if (btnContext[5].trim() === "0") {
        const deleteform = document.createElement('form');
        deleteform.id = "deletebookForm";
        deleteform.action = "";

        const buttonDeleteContainer = document.createElement('div');
        buttonDeleteContainer.classList.add('flex', 'justify-center');

        const buttonDelete = document.createElement('button');
        buttonDelete.classList.add('mt-4', 'px-4', 'py-2', 'bg-red-500', 'text-white', 'rounded-lg', 'hover:bg-red-400');
        buttonDelete.textContent = "Supprimer le livre";
        buttonDeleteContainer.appendChild(buttonDelete);

        deleteform.appendChild(idBook);
        deleteform.appendChild(buttonDeleteContainer);

        deleteform.onsubmit = (e) => {
            e.preventDefault();

            const id_book = document.getElementById('id_book').value;
            fetch('/api/deletebook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({id_book})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const responseDiv = document.getElementById('responseDiv');
                        responseDiv.classList.add('text-green-500');
                        responseDiv.textContent = "Livre supprimé avec succès";
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-red-500');
                    responseDiv.textContent = "Erreur lors de la suppression du livre";
                });
        };

        modalFormDiv.appendChild(deleteform);
    }

    toggleModal();
}
