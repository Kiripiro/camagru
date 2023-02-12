function toggleUpdateLogin() {
    var target = document.getElementById("update-login");
    var arrow = document.getElementById("arrow");
    if (target.style.display == 'block') {
        target.style.display = 'none';
        arrow.classList.remove("fa-chevron-up");
        arrow.classList.add("fa-chevron-down");
    }
    else {
        target.style.display = 'block';
        arrow.classList.remove("fa-chevron-down");
        arrow.classList.add("fa-chevron-up");
    }
}

function toggleUpdatePassword() {
    var target = document.getElementById("update-password");
    var arrow = document.getElementById("arrow");
    if (target.style.display == 'block') {
        target.style.display = 'none';
        arrow.classList.remove("fa-chevron-up");
        arrow.classList.add("fa-chevron-down");
    }
    else {
        target.style.display = 'block';
        arrow.classList.remove("fa-chevron-down");
        arrow.classList.add("fa-chevron-up");
    }
}