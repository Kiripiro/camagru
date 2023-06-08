document.addEventListener('DOMContentLoaded', () => {

    const navbarBurger = document.querySelector('.navbar-burger');
    const target = document.getElementById(navbarBurger.dataset.target);

    navbarBurger.addEventListener('click', () => {
        navbarBurger.classList.toggle('is-active');
        target.classList.toggle('is-active');
    });

    const showModalButton = document.querySelector("#navbar-search-button");
    const modal = document.querySelector("#search-modal");

    showModalButton.addEventListener("click", () => {
        modal.classList.add("is-active");
    });

    const modalBackground = document.querySelector("#navbar-modal-background");

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

const searchInput = document.getElementById("searchbar");
const searchInputMobile = document.getElementById("searchbar-mobile");
const searchUser = () => {
    const username = searchInput.value || searchInputMobile.value;
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
    xhr.open('POST', '/search-user', true);
    var formData = new FormData();
    formData.append('token', token);
    formData.append('username', username);

    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                if (response.isProfile == 1)
                    window.location.href = "/profile";
                else
                    window.location.href = "/userProfile?username=" + username;
            } else {
                searchInput.value = "";
                searchInput.classList.add("is-danger") || searchInputMobile.classList.add("is-danger");
                setTimeout(() => {
                    searchInput.classList.remove("is-danger") || searchInputMobile.classList.remove("is-danger");
                }, 2000);
            }
        } else {
            console.log(response.error);
        }
    }
}

function handleKeyPress(event) {
    if (event.key === "Enter") {
        searchUser();
    }
}