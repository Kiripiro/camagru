
const showComments = (index) => {
    const mediaBottom = document.getElementById(`media-bottom-${index}`);
    const mediaComments = document.getElementById(`media-comments-${index}`);
    mediaBottom.classList.add('is-hidden');
    mediaComments.classList.remove('is-hidden');
}

const hideComments = (index) => {
    const mediaBottom = document.getElementById(`media-bottom-${index}`);
    const mediaComments = document.getElementById(`media-comments-${index}`);
    mediaBottom.classList.remove('is-hidden');
    mediaComments.classList.add('is-hidden');
}

const redirect = (login, isProfile) => {
    if (isProfile == 1) {
        window.location.href = `/profile`;
    } else {
        window.location.href = `/userProfile?login=${login}`;
    }
}

const loader = document.querySelector(".lds-ring-gallery");
const sentinel = document.getElementById('sentinel');
let isLoading = false;
let isObserving = true;

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting && !isLoading && isObserving) {
            isLoading = true;
            loader.classList.remove('is-hidden');
            setTimeout(loadMoreContent, 1000);
        }
    });
}, {
    root: null,
    threshold: 0.8,
});

observer.observe(sentinel);

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

function loadMoreContent() {
    var token = getCookie("token");
    var offset = document.getElementById('gallery').childElementCount;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/galleryLoad', true);
    var formData = new FormData();
    formData.append('token', token);
    formData.append('offset', offset);

    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = xhr.responseText;
            if (response)
                document.getElementById('gallery').insertAdjacentHTML('beforeend', response);
        } else if (xhr.status === 204) {
            isObserving = false;
        } else if (xhr.status === 401) {
            console.log("Unauthorized");
        } else {
            console.log("Error", xhr.status);
        }
        loader.classList.add('is-hidden');
        isLoading = false;
    };
}