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

const searchInput = document.getElementById("searchbar");
const searchInputMobile = document.getElementById("searchbar-mobile");
const searchUser = () => {
    const userLogin = searchInput.value || searchInputMobile.value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/search-user', true);
    var token = getCookie("token");

    if (token === "") {
        console.log("No token found");
        return;
    }
    var formData = new FormData();
    formData.append('userLogin', userLogin);
    formData.append('token', token);

    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                window.location.href = "https://camagru.fr/userProfile?login=" + userLogin;
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