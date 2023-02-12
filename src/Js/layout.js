window.onload = function () {
    if (navigator.userAgent.indexOf('Mac OS X') != -1) {
        document.getElementById("searchbar").setAttribute("placeholder", "Rechercher... (⌘ + K)");
    } else if ((navigator.userAgent.indexOf('Linux') != -1) || (navigator.userAgent.indexOf('Windows') != -1)) {
        document.getElementById("searchbar").setAttribute("placeholder", "Rechercher... (CTRL + K)");
    } else if ((navigator.userAgent.indexOf('Android') != -1) || (navigator.userAgent.indexOf('iPhone') != -1)) {
        document.getElementById("searchbar").setAttribute("placeholder", "Rechercher...");
    }
    window.addEventListener('keydown', function (e) {
        if (e.metaKey && e.key == 'k') {
            e.preventDefault();
            document.getElementById("searchbar").focus();
        }
    });
}