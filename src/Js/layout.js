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

function getCookie(cookieName) {
    var cookies = document.cookie.split(";");
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(cookieName + "=") === 0) {
            return cookie.substring(cookieName.length + 1);
        }
    }
    return "";
}

function likePost(postId) {
    var token = getCookie("token");

    if (token === "") {
        var phpSessionId = getCookie("PHPSESSID");
        if (phpSessionId !== "") {
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
        window.location.href = "/login";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/like', true);
    var formData = new FormData();
    formData.append('token', token);
    formData.append('post_id', postId);

    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                if (response.message == "Like ajouté") {
                    document.getElementById(`like-${postId}`).classList.add('is-hidden');
                    document.getElementById(`unlike-${postId}`).classList.remove('is-hidden');
                    let count = parseInt(document.getElementById(`like-count-${postId}`).innerHTML) + 1;
                    document.getElementById(`like-count-${postId}`).innerHTML = count + (count > 1 ? " Likes" : " Like");
                }
                else if (response.message == "Like supprimé") {
                    document.getElementById(`like-${postId}`).classList.remove('is-hidden');
                    document.getElementById(`unlike-${postId}`).classList.add('is-hidden');
                    let count = parseInt(document.getElementById(`like-count-${postId}`).innerHTML) - 1;
                    document.getElementById(`like-count-${postId}`).innerHTML = count + (count > 1 ? " Likes" : " Like");
                }
                showSnackbar(response.message, "success");
            } else {
                console.log(response.error);
            }
        } else if (xhr.status === 401) {
            console.log("Unauthorized");
        } else {
            console.log("Error", xhr.status);
        }
    };
}

function addComment(postId) {
    var token = getCookie("token");

    if (token === "") {
        var phpSessionId = getCookie("PHPSESSID");
        if (phpSessionId !== "") {
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
        window.location.href = "/login";
        return;
    }

    var commentInput = document.getElementById(`new-comment-${postId}`);
    var comment = document.getElementById(`new-comment-${postId}`).value;

    if (comment === "") {
        showSnackbar("Commentaire vide", "error");
        return;
    }
    commentInput.disabled = true;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/comment', true);
    var formData = new FormData();
    formData.append('token', token);
    formData.append('post_id', postId);
    formData.append('comment', comment);

    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                let count = parseInt(document.getElementById(`comment-count-${postId}`).innerHTML) + 1;
                document.getElementById(`comment-count-${postId}`).innerHTML = count + (count > 1 ? " Comments" : " Comment");
                let comments = document.getElementById(`comments-${postId}`);
                var commentContainer = document.createElement("div");
                commentContainer.className = "comments container";
                commentContainer.innerHTML = `
                    <div class="columns">
                        <div class="column is-4">
                            <label class="label">${response.user}:</label>
                        </div>
                        <div class="column is-6">
                            <p class="text">${response.comment}</p>
                        </div>
                    </div>
                `;
                comments.appendChild(commentContainer);
                comments.insertAdjacentHTML('beforeend', '<hr>');
                document.getElementById(`new-comment-${postId}`).value = "";
            } else {
                console.log(response);
            }
            showSnackbar(response.message, "success");
            commentInput.disabled = false;
        } else if (xhr.status === 401) {
            console.log("Unauthorized");
        } else {
            console.log("Error", xhr.status);
        }
    };
}

function handleKeyPressComment(event, postId) {
    if (event.key === "Enter") {
        addComment(postId);
    }
}