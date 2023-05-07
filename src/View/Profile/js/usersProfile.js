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