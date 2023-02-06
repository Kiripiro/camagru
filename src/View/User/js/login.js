const form = document.querySelector('.login-form');
const inputs = form.querySelectorAll('input[type="text"], input[type="password"]');
const errorMessage = document.createElement("p");
errorMessage.style.color = "red";
form.appendChild(errorMessage);

form.addEventListener('submit', (event) => {
    event.preventDefault();
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value) {
            isValid = false;
            input.style.border = "1px solid red";
        } else {
            input.style.border = "";
        }
    });

    if (isValid) {
        form.submit();
    } else {
        errorMessage.textContent = "Tous les champs sont requis.";
    }
});