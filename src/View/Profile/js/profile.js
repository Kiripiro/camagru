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

const deletePostProfile = (index, post_id) => {
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
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                const post = document.getElementById(`post_${index}`);
                const box = document.getElementById(`box_${index}`);
                post.remove();
                box.remove();
                console.log('Post deleted');
            } else {
                console.log('Error:', response);
            }
        } else {
            console.log('Error:', xhr.status);
        }
    }
}