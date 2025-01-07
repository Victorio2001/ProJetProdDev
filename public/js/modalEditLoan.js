const modal = document.getElementById('modal-overlay');

function openLoanModal(e) {
    const modalFormDiv = document.getElementById('modal_form_div');
    modalFormDiv.innerText = '';

    const modalTitle = document.getElementById('modal_title');
    modalTitle.textContent = "Modifier une réservation";

    const form = document.createElement('form');
    form.id = "loanForm";
    form.action = "";

    const deleteform = document.createElement('form');
    deleteform.id = "deleteloanForm";
    deleteform.action = "";

    const loanback = document.createElement('form');
    loanback.id = "loanback";
    loanback.action = "";

    const btnContext = e.getAttribute("data-context").split(';');

    const idBook = document.createElement('input');
    idBook.id = "id_book"
    idBook.name = "id_book"
    idBook.type = "hidden"
    idBook.value = btnContext[0].trim();

    const idUser = document.createElement('input');
    idUser.id = "id_user"
    idUser.name = "id_user"
    idUser.type = "hidden"
    idUser.value = btnContext[1].trim();

    const loanReturn = document.createElement('input');
    loanReturn.id = "loanReturn"
    loanReturn.name = "loanReturn"
    loanReturn.type = "hidden"
    loanReturn.value = btnContext[3].trim();

    form.appendChild(idBook);
    form.appendChild(idUser);
    loanback.appendChild(idBook);
    loanback.appendChild(idUser);
    loanback.appendChild(loanReturn);
    deleteform.appendChild(loanReturn);

    const responseDiv = document.createElement('div');
    responseDiv.id = "responseDiv";


    const buttondelete = document.createElement('button');
    buttondelete.classList.add('mt-4', 'px-4', 'py-2', 'bg-red-500', 'items-center', 'text-white', 'rounded-lg', 'hover:bg-red-700');
    buttondelete.textContent = "Annuler la réservation";


    if (btnContext[2].trim() === '#3CC4FF') {
        const buttonback = document.createElement('button');
        buttonback.classList.add('mt-4', 'px-4', 'py-2', 'bg-primary-900', 'items-center', 'text-white', 'rounded-lg', 'hover:bg-primary-700');
        buttonback.textContent = "Marquer le livre comme rendu";
        loanback.appendChild(buttonback);
        deleteform.appendChild(buttondelete)

        loanback.onsubmit = (e) => {
            e.preventDefault();

            const id_book = document.getElementById('id_book').value;
            const id_user = document.getElementById('id_user').value;
            const loanReturn = document.getElementById('loanReturn').value;

            fetch('/api/loanSecondStep', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({id_book, id_user,loanReturn})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const responseDiv = document.getElementById('responseDiv');
                        responseDiv.classList.add('text-green-500');
                        responseDiv.textContent = "Emprunt du livre confirmé";
                        location.reload();

                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-red-500');
                    responseDiv.textContent = "Erreur lors de l'emprunt du livre";
                });

        };

    }

    if (btnContext[2].trim() === '#E4FF3C') {
        const button = document.createElement('button');
        button.classList.add('mt-4', 'px-4', 'py-2', 'bg-primary-900', 'text-white', 'rounded-lg', 'hover:bg-primary-700');
        button.textContent = 'Marquer le livre comme emprunté';
        form.appendChild(button);
        deleteform.appendChild(buttondelete);

        form.onsubmit = (e) => {
            e.preventDefault();

            const id_book = document.getElementById('id_book').value;
            const id_user = document.getElementById('id_user').value;

            fetch('/api/loanFirstStep', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({id_book, id_user})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const responseDiv = document.getElementById('responseDiv');
                        responseDiv.classList.add('text-green-500');
                        responseDiv.textContent = "Emprunt du livre confirmé";
                        location.reload();

                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-red-500');
                    responseDiv.textContent = "Erreur lors de l'emprunt du livre";
                });

        };

    }


    deleteform.onsubmit = (e) => {
        e.preventDefault();

        const id_book = document.getElementById('id_book').value;
        const id_user = document.getElementById('id_user').value;
        const loanReturn = document.getElementById('loanReturn').value;
        fetch('/api/cancelLoan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({id_book, id_user,loanReturn})
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const responseDiv = document.getElementById('responseDiv');
                    responseDiv.classList.add('text-green-500');
                    responseDiv.textContent = "Réservation annulée avec succes";
                    location.reload();

                }
            })
            .catch(error => {
                console.error('Error:', error);

                const responseDiv = document.getElementById('responseDiv');
                responseDiv.classList.add('text-red-500');
                responseDiv.textContent = "Erreur lors de l\'annulation de la réservation";

            });

    };
    form.appendChild(responseDiv);
    modalFormDiv.appendChild(deleteform);
    modalFormDiv.appendChild(form);
    modalFormDiv.appendChild(loanback);
    toggleModal();
}
