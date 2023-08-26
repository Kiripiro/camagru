window.onload = function () {
    const searchbar = document.getElementById("searchbar");
    if (searchbar) {
        if (navigator.userAgentData) {
            if (navigator.userAgentData.platform === "Mac" || navigator.userAgentData.platform === "macOS") {
                searchbar.setAttribute("placeholder", "Find user... (⌘ + K)");
            } else if ((navigator.userAgentData.platform === "Linux") || (navigator.userAgentData.platform === "Windows")) {
                searchbar.setAttribute("placeholder", "Find user... (CTRL + K)");
            }
        } else {
            searchbar.setAttribute("placeholder", "Find user... (CTRL + K)");
        }

        window.addEventListener('keydown', function (e) {
            if (e.metaKey && e.key == 'k' || e.ctrlKey && e.key == 'k') {
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
                    if (input.name === "biography") {
                        if (input.value.length > 255) {
                            isValid = false;
                            input.style.border = "1px solid red";
                            errorMessage.textContent = "Biography must be less than 255 characters.";
                        } else {
                            input.style.border = "";
                        }
                    }
                    if (!input.value && input.name !== "biography") {
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
                        errorMessage.textContent = "This field is required.";
                    } else {
                        errorMessage.textContent = "All fields are required.";
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
                if (response.message == "Like has been added") {
                    document.getElementById(`like-${postId}`).classList.add('is-hidden');
                    document.getElementById(`unlike-${postId}`).classList.remove('is-hidden');
                    let count = parseInt(document.getElementById(`like-count-${postId}`).innerHTML) + 1;
                    document.getElementById(`like-count-${postId}`).innerHTML = count + (count > 1 ? " Likes" : " Like");
                }
                if (response.message == "Like has been removed") {
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
    var hasBeenCommented = document.getElementById(`comment-count-${postId}`).innerHTML;
    let countComments = parseInt(hasBeenCommented.split(" ")[0]);

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
        showSnackbar("Empty comment", "danger");
        return;
    } else if (comment.length > 255) {
        showSnackbar("Comment too long (max 255 char.)", "danger");
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
                if (countComments !== 0)
                    comments.insertAdjacentHTML('beforeend', '<hr>');
                commentContainer.innerHTML = `
                    <div class="columns is-mobile">
                        <div class="column is-3">
                            <label class="label">${response.user}:</label>
                        </div>
                        <div class="column is-7">
                            <p class="comment">${response.comment}</p>
                        </div>
                    </div>
                `;
                comments.appendChild(commentContainer);
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

async function loadMime(file) {
    var mimes = [
        {
            mime: 'image/jpeg',
            pattern: [0xFF, 0xD8, 0xFF],
        },
        {
            mime: 'image/png',
            pattern: [0x89, 0x50, 0x4E, 0x47],
        }
    ];

    function convertToHex(bytes)
    {
        var arr = [];
        bytes.forEach(function(byte) {
            arr.push('0x' + ('0' + (byte & 0xFF).toString(16).toLocaleUpperCase()).slice(-2));
        });
        return arr;
    }

    function checkTypes(hexBytes)
    {
        for(i = 0; i < 2; i++) {
            if (mimes[i].mime == file.type)
                if (hexBytes.toString() == convertToHex(mimes[i].pattern).toString())
                    return true;
            return false;
        }
    }

    if (file.type == 'image/jpeg')
        var blob = file.slice(0, 3);
    else
        var blob = file.slice(0, 4);

    let hexBytesPromise = new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.addEventListener("loadend", () => {
            var bytes = new Uint8Array(reader.result);
            let hexBytes = convertToHex(bytes);
            resolve(hexBytes);
        });
        reader.readAsArrayBuffer(blob);
    });
    let hexBytes = await hexBytesPromise;

    return checkTypes(hexBytes);
}
