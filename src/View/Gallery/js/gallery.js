
const showComments = (index) => {
    console.log('showComments', index);
    const mediaBottom = document.getElementById(`media-bottom-${index}`);
    const mediaComments = document.getElementById(`media-comments-${index}`);
    console.log('showComments', mediaBottom);
    console.log(mediaComments);
    mediaBottom.classList.add('is-hidden');
    mediaComments.classList.remove('is-hidden');
}

const hideComments = (index) => {
    console.log('hideComments', index);
    const mediaBottom = document.getElementById(`media-bottom-${index}`);
    const mediaComments = document.getElementById(`media-comments-${index}`);
    console.log('hideComments', mediaBottom);
    console.log(mediaComments);
    mediaBottom.classList.remove('is-hidden');
    mediaComments.classList.add('is-hidden');
}