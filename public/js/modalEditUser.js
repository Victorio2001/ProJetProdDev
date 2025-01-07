const modal = document.getElementById('modal-overlay');


function openUserModal(e) {
    const modalFormDiv = document.getElementById('modal_form_div');
    modalFormDiv.innerText = '';

    const modalTitle = document.getElementById('modal_title');
    modalTitle.textContent = "Modifier un Utilisateur";

    const form = document.createElement('form');
    form.id = "bookForm";
    form.action = "";

    const btnContext = e.getAttribute("data-context").split(';');

    const idUser = document.createElement('input');
    idUser.id = "id_user"
    idUser.name = "id_user"
    idUser.type = "hidden"
    idUser.value = btnContext[0].trim();

    form.appendChild(idUser);

    const div = document.createElement('div');
    div.classList.add('mb-4', 'relative');

    const label = document.createElement('label');
    label.for = "name";
    label.classList.add('block');
    label.textContent = "Nom de l'utilisateur :"

    const labelfirstname = document.createElement('label');
    labelfirstname.for = "firstname";
    labelfirstname.classList.add('block');
    labelfirstname.textContent = "Prénom de l'utilisateur :";

    const labelemail = document.createElement('label');
    labelemail.for = "email";
    labelemail.classList.add('block');
    labelemail.textContent = "Email de l'utilisateur :";

    const name = document.createElement('input');
    name.type = "text";
    name.id = "name";
    name.name = "name";
    name.placeholder = "Nom de l'utilisateur";
    name.classList.add('p-2', 'rounded-md', 'border', 'w-96', 'border-blue-300');
    name.required = true;
    name.defaultValue = btnContext[1].trim()

    const firstname = document.createElement('input');
    firstname.type = "text";
    firstname.id = "firstname";
    firstname.name = "firstname";
    firstname.placeholder = "Prénom de l'utilisateur";
    firstname.classList.add('p-2', 'rounded-md', 'w-96', 'border', 'border-blue-300');
    firstname.required = true;
    firstname.defaultValue = btnContext[2].trim()

    const email = document.createElement('input');
    email.type = "text";
    email.id = "emailinput";
    email.name = "email";
    email.placeholder = "Email de l'utilisateur";
    email.classList.add('p-2', 'rounded-md', 'w-96', 'border', 'border-blue-300');
    email.required = true;
    email.defaultValue = btnContext[3].trim()


    div.appendChild(label);
    div.appendChild(name);

    div.appendChild(labelfirstname);
    div.appendChild(firstname);

    div.appendChild(labelemail);
    div.appendChild(email);

    form.appendChild(div);

    const responseDiv = document.createElement('div');
    responseDiv.id = "responseDiv";

    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('flex', 'justify-center');

    const button = document.createElement('button');
    button.classList.add('mt-4', 'px-4', 'py-2', 'bg-primary-900', 'text-white', 'rounded-lg', 'hover:bg-primary-700');
    button.textContent = "Modifier l'utilisateur";

    buttonContainer.appendChild(button);


    form.appendChild(buttonContainer);
    form.appendChild(responseDiv);

    form.onsubmit = (e) => {
        e.preventDefault();

        const id_user = document.getElementById('id_user').value;
        const name = document.getElementById('name').value;
        const firstname = document.getElementById('firstname').value;
        const email = document.getElementById('emailinput').value;
        fetch('/api/user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({id_user, name, firstname, email})
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-green-500');
                    responseDiv.textContent = "Utilisateur modifié avec succès";
                    location.reload();

                }
            })
            .catch(error => {
                console.error('Error:', error);

                const responseDiv = document.getElementById('responseDiv');
                responseDiv.classList.add('text-red-500');
                responseDiv.textContent = "Erreur lors de la modification de l'utilisateur ";
            });
    };

    modalFormDiv.appendChild(form);

    const deleteform = document.createElement('form');
    deleteform.id = "deletebookForm";
    deleteform.action = "";

    const buttonDeleteContainer = document.createElement('div');
    buttonDeleteContainer.classList.add('flex', 'justify-center');

    const buttonDelete = document.createElement('button');
    buttonDelete.classList.add('mt-4', 'px-4', 'py-2', 'bg-red-500', 'text-white', 'rounded-lg', 'hover:bg-red-400');
    buttonDelete.textContent = "Archiver l'utilisateur";
    buttonDeleteContainer.appendChild(buttonDelete);

    deleteform.appendChild(idUser);
    deleteform.appendChild(buttonDeleteContainer);

    deleteform.onsubmit = (e) => {
        e.preventDefault();

        const id_user = document.getElementById('id_user').value;
        fetch('/api/archivuser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({id_user})
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



    toggleModal();
}
