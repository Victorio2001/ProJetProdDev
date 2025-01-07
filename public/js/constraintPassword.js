function validatePassword() {
    const password = document.getElementById("mdp").value;
    const confirmPassword = document.getElementById("mdpConfirm").value;

    const lengthCheck = document.getElementById("lengthCheck");
    const uppercaseCheck = document.getElementById("uppercaseCheck");
    const lowercaseCheck = document.getElementById("lowercaseCheck");
    const specialCharCheck = document.getElementById("specialCharCheck");
    const numberCheck = document.getElementById("numberCheck");
    const matchCheck = document.getElementById("matchCheck");

    lengthValidated = password.length >= 14;
    uppercaseValidated = /[A-Z]/.test(password);
    lowercaseValidated = /[a-z]/.test(password);
    specialCharValidated = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    numberValidated = /\d/.test(password);
    matchValidated = password === confirmPassword;

    updateValidationStatus(lengthCheck, lengthValidated);
    updateValidationStatus(uppercaseCheck, uppercaseValidated);
    updateValidationStatus(lowercaseCheck, lowercaseValidated);
    updateValidationStatus(specialCharCheck, specialCharValidated);
    updateValidationStatus(numberCheck, numberValidated);
    updateValidationStatus(matchCheck, matchValidated);

    checkFormValidity();
}

function updateValidationStatus(element, isValid) {
    if (isValid) {
        element.innerHTML = '✅ ' + element.textContent.replace('✅ ', '').replace('❌ ', '');
        element.style.color = 'green';
    } else {
        element.innerHTML = '❌ ' + element.textContent.replace('✅ ', '').replace('❌ ', '');
        element.style.color = 'red';
    }
}

function checkFormValidity() {
    const submitBtn = document.getElementById("submitBtn");
    if (lengthValidated && uppercaseValidated && lowercaseValidated && specialCharValidated && numberValidated && matchValidated) {
        submitBtn.removeAttribute("disabled");
        submitBtn.style.background = 'bg-primary-900';
        submitBtn.classList.remove("bg-gray-200");
        submitBtn.classList.add("bg-primary-900");
    } else {
        submitBtn.setAttribute("disabled", "disabled");
        submitBtn.classList.remove("bg-primary-900");
        submitBtn.classList.add("bg-gray-200");
    }
}

validatePassword();