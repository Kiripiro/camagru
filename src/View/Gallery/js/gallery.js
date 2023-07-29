
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

const redirect = (username, isProfile) => {
    if (isProfile == 1) {
        window.location.href = `/profile`;
    } else {
        window.location.href = `/userProfile?username=${username}`;
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
