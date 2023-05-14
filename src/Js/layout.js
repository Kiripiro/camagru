window.onload = function () {
    const searchbar = document.getElementById("searchbar");
    if (searchbar) {
        if (navigator.userAgent.indexOf('Mac OS X') != -1) {
            searchbar.setAttribute("placeholder", "Rechercher... (⌘ + K)");
        } else if ((navigator.userAgent.indexOf('Linux') != -1) || (navigator.userAgent.indexOf('Windows') != -1)) {
            searchbar.setAttribute("placeholder", "Rechercher... (CTRL + K)");
        }
        window.addEventListener('keydown', function (e) {
            if (e.metaKey && e.key == 'k') {
                e.preventDefault();
                searchbar.focus();
            }
        });
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                searchbar.blur();
            }
        });
    }

    const forms = document.querySelectorAll('.form');
    if (forms) {
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], textarea');

            const errorMessageContainer = document.createElement("div");
            errorMessageContainer.style.marginTop = "3vh";

            const errorMessage = document.createElement("p");
            errorMessage.style.color = "red";

            errorMessageContainer.appendChild(errorMessage);

            const modalBody = form.querySelector(".modal-card-body");
            if (modalBody) {
                modalBody.appendChild(errorMessageContainer);
            } else {
                form.appendChild(errorMessageContainer);
            }

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
                    if (inputs.length === 1) {
                        errorMessage.textContent = "Ce champ est requis.";
                    } else {
                        errorMessage.textContent = "Tous les champs sont requis.";
                    }
                }
            });
        });
    }

    const togglePasswords = document.querySelectorAll('.toggle-password');
    const passwords = document.querySelectorAll('input[type="password"]');
    if (togglePasswords && passwords) {
        togglePasswords.forEach(function (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const passwordField = this.closest('.field');
                let password = passwordField.querySelector('input[type="password"]');
                if (!password)
                    password = passwordField.querySelector('input[type="text"]');
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    }

};