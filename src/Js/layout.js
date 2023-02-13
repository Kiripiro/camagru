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

    const form = document.querySelector('.form');
    if (form) {
        const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');

        const errorMessageContainer = document.createElement("div");
        errorMessageContainer.style.marginTop = "3vh";

        const errorMessage = document.createElement("p");
        errorMessage.style.color = "red";

        errorMessageContainer.appendChild(errorMessage);
        form.appendChild(errorMessageContainer);

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
    }
};