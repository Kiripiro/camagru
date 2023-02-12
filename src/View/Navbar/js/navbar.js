document.addEventListener('DOMContentLoaded', () => {

    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    $navbarBurgers.forEach(el => {
        el.addEventListener('click', () => {

            const target = el.dataset.target;
            const $target = document.getElementById(target);

            el.classList.toggle('is-active');
            $target.classList.toggle('is-active');

        });
    });

    const showModalButton = document.querySelector("#navbar-search-button");
    const modal = document.querySelector("#search-modal");

    showModalButton.addEventListener("click", () => {
        modal.classList.add("is-active");
    });

    const modalBackground = document.querySelector(".modal-background");

    modalBackground.addEventListener("click", () => {
        modal.classList.remove("is-active");
    });

    modalBackground.addEventListener("touchstart", () => {
        modal.classList.remove("is-active");
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024 && modal.classList.contains("is-active")) {
            modal.classList.remove("is-active");
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            modal.classList.remove("is-active");
        }
    });
});