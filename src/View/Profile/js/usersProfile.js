const showComments = (index) => {
    const comments = document.getElementById(`comments_${index}`);
    comments.classList.remove('is-hidden');
}

const hideComments = (index) => {
    const comments = document.getElementById(`comments_${index}`);
    comments.classList.add('is-hidden');
}
