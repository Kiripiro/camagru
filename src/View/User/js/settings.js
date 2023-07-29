document.addEventListener('DOMContentLoaded', () => {
    const menuItems = {
        myProfileMenu: document.querySelector('.my-profile-menu'),
        profilePicMenu: document.querySelector('.profile-pic-menu'),
        loginMenu: document.querySelector('.login-menu'),
        emailMenu: document.querySelector('.email-menu'),
        biographyMenu: document.querySelector('.biography-menu'),
        deleteMenu: document.querySelector('.delete-menu'),
        securityMenu: document.querySelector('.security-menu'),
        updatePasswordMenu: document.querySelector('.update-password-menu'),
        notificationsMenu: document.querySelector('.notifications-menu'),
        emailNotificationsMenu: document.querySelector('.email-notifications-menu')
    };

    menuItems.myProfileMenu.classList.toggle('is-active');

    Object.values(menuItems).forEach((menuItem) => {
        menuItem.addEventListener('click', (e) => {
            e.preventDefault();
            if (!menuItem.classList.contains('is-active')) {
                Object.values(menuItems).forEach((item) => {
                    if (item.classList.contains('is-active')) {
                        item.classList.remove('is-active');
                    }
                });
                menuItem.classList.add('is-active');
                const target = document.querySelector(menuItem.getAttribute('href'));
                window.scrollTo({
                    top: target.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    const deleteButton = document.querySelector('#delete-button');
    const modal = document.querySelector("#delete-modal");

    deleteButton.addEventListener("click", () => {
        modal.classList.add("is-active");
    });

    const modalBackground = document.querySelector("#settings-modal-background");

    modalBackground.addEventListener("click", () => {
        modal.classList.remove("is-active");
    });

    modalBackground.addEventListener("touchstart", () => {
        modal.classList.remove("is-active");
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            modal.classList.remove("is-active");
        }
    });

    const fileInput = document.querySelector('#file-upload input[type=file]');
    fileInput.onchange = () => {
        if (fileInput.files[0].size > 2 * 1024 * 1024) {
            alert('The image\'s size shouldn\'t be above 2 Mo.');
            fileInput.files[0].value = null;
            return;
        }
        if (fileInput.files.length > 0) {
            const fileName = document.querySelector('#file-upload .file-name');
            fileName.textContent = fileInput.files[0].name;
        }
    }
});