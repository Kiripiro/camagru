function showSnackbar(message, type) {
    var snackbar = document.getElementById("snackbar");
    var snackbarMessage = document.getElementById("snackbar-message");
    var snackbarDelete = document.getElementById("snackbar-delete");
    var progressBar = document.querySelector("#snackbar .progress-bar");

    snackbarDelete.addEventListener("click", function () {
        snackbar.style.display = "none";
    });

    snackbarMessage.innerHTML = message;
    snackbar.classList.remove("is-danger", "is-success");
    snackbar.classList.add("is-" + type);
    snackbar.removeAttribute("hidden");

    snackbar.style.display = "block";

    if (!progressBar) {
        var progress = document.createElement("div");
        progress.classList.add("progress", "is-small");

        progressBar = document.createElement("div");
        progressBar.classList.add("progress-bar");

        progress.appendChild(progressBar);
        snackbar.appendChild(progress);

        progressBar.style.animation = "progress 5s linear forwards";
    }

    setTimeout(function () {
        snackbar.style.display = "none";
    }, 5000);
}
