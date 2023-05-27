const showComments = (index) => {
    const post = document.getElementById(`post_${index}`);
    const comments = document.getElementById(`comments_${index}`);
    post.classList.add('is-hidden');
    comments.classList.remove('is-hidden');
}

const hideComments = (index) => {
    const post = document.getElementById(`post_${index}`);
    const comments = document.getElementById(`comments_${index}`);
    post.classList.remove('is-hidden');
    comments.classList.add('is-hidden');
}

function deletePostProfile(post_id) {
    var deleteModal = document.getElementById("deleteModal");
    deleteModal.classList.add("is-active");

    var confirmDeleteBtn = document.getElementById("confirmDelete");
    var cancelDeleteBtn = document.getElementById("cancelDelete");
    var closeModalBtn = document.getElementById("closeModal");

    confirmDeleteBtn.addEventListener("click", function () {
        deletePostProfileConfirmation(post_id);

        deleteModal.classList.remove("is-active");
    });

    cancelDeleteBtn.addEventListener("click", function () {
        deleteModal.classList.remove("is-active");
    });

    closeModalBtn.addEventListener("click", function () {
        deleteModal.classList.remove("is-active");
    });
}


const deletePostProfileConfirmation = (post_id) => {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/delete-post', true);
    var token = getCookie("token");

    if (token === "") {
        console.log("No token found");
        return;
    }
    var formData = new FormData();
    formData.append('pictureId', post_id);
    formData.append('token', token);

    xhr.send(formData);

    xhr.onload = function () {
        const response = JSON.parse(xhr.responseText);
        if (xhr.status === 200) {
            if (response.success) {
                const post = document.getElementById(`post_${post_id}`);
                const box = document.getElementById(`box_${post_id}`);
                post.remove();
                box.remove();
                showSnackbar(response.message, "success");
            } else {
                showSnackbar(response.message, "danger");
            }
        } else {
            showSnackbar(response.message, "danger");
        }
    }
}