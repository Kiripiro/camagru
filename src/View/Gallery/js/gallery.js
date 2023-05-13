
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