const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
const video = document.getElementById("video");
const deleteFilterButton = document.getElementById("delete-filter");
const deletePhotoButton = document.getElementById("delete-photo");
const takeSnapshotDiv = document.getElementsByClassName("take-snapshot");
const takeSnapshotButton = document.getElementById("take-snapshot");
takeSnapshotButton.setAttribute("disabled", "disabled");
const useImageButton = document.getElementById("use-image");
const createImageDiv = document.getElementsByClassName("create-image");
const uploadImageDiv = document.getElementsByClassName("upload-image");
const image = document.getElementById("image");
let imgLoaded = false;
let filtersBackup = [];
let imageBackup = null;
let images = [];
let filters = [];
let filter = null;
let isDragging = false;
let isFilterClicked = false;
let isSnapshotTaken = false;
let prevX = null;
let prevY = null;

video.addEventListener('loadedmetadata', () => {
    canvas.width = video.getBoundingClientRect().width;
    canvas.height = video.getBoundingClientRect().height;
    video.style = "display: none";
});

window.addEventListener("resize", () => {
    video.style = "display: block";
    canvas.width = video.getBoundingClientRect().width;
    canvas.height = video.getBoundingClientRect().height;
    video.style = "display: none";
    filters.forEach((filter) => {
        const filterRatio = filter.image.width / filter.image.height;
        const filterWidth = canvas.width / 4;
        const filterHeight = filterWidth / filterRatio;
        filter.x = filter.x * filterWidth / filter.width;
        filter.y = filter.y * filterHeight / filter.height;
        filter.width = filterWidth;
        filter.height = filterHeight;
    });
});

navigator.mediaDevices.enumerateDevices()
    .then(function (devices) {
        const videoDevices = devices.filter(function (device) {
            return device.kind === "videoinput";
        });
        if (videoDevices.length > 0) {
            navigator.mediaDevices.getUserMedia = navigator.mediaDevices.getUserMedia ||
                navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    if (stream) {
                        const video = document.getElementById("video");
                        video.srcObject = stream;
                        imgLoaded = true;
                        video.play();

                        requestAnimationFrame(draw);
                    }
                })
                .catch(function (err) {
                    console.log("An error has occurred: " + err);
                });
        } else {
            canvas.style = "display: block";
            canvas.width = video.getBoundingClientRect().width;;
            canvas.height = video.getBoundingClientRect().height;
            video.style = "display: none";
            console.log("No video devices found");
        }
    })
    .catch(function (err) {
        console.log(err.name + ": " + err.message);
    });

const input = document.querySelector('#file-upload input[type=file]');
input.addEventListener("change", async function (event) {
    let res = await loadMime(event.target.files[0]);
    if (!res) {
        showSnackbar('The image\'s format should be PNG or JPEG.', "danger");
        event.target.files[0].value = null;
        return ;
    }

    if (input.files.length > 0) {
        const fileName = document.querySelector('#file-upload .file-name');
        fileName.textContent = input.files[0].name;
    }
    filters = [];
    const file = event.target.files[0];
    const image = new Image();
    image.src = URL.createObjectURL(file);
    imageBackup = image;
    images.push(image);
    image.onload = function () {
        const imageRatio = image.width / image.height;
        const imageWidth = canvas.width;
        const imageHeight = imageWidth / imageRatio;
        canvas.height = imageHeight;
        ctx.drawImage(image, 0, 0, imageWidth, imageHeight);
    };
    requestAnimationFrame(drawFromImage);
});

const drawFromImage = () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    images.forEach((image) => {
        const imageRatio = image.width / image.height;
        const imageWidth = canvas.width;
        const imageHeight = imageWidth / imageRatio;
        ctx.drawImage(image, 0, 0, imageWidth, imageHeight);
    });
    if (filters)
        filters.forEach((filter) => {
            if (filter && isFilterClicked) {
                drawFilterBorder();
            }
            ctx.drawImage(filter.image, filter.x, filter.y, filter.width, filter.height);
        });
    if (isFilterClicked) {
        deleteFilterButton.removeAttribute("disabled");
    } else {
        deleteFilterButton.setAttribute("disabled", "disabled");
    }
    requestAnimationFrame(drawFromImage);
};

const createFilter = (filterImage) => {
    const filterRatio = filterImage.width / filterImage.height;
    const filterWidth = canvas.width / 4;
    const filterHeight = filterWidth / filterRatio;
    let name = filterImage.src.split("/").pop();

    filters.push({
        name: name,
        image: filterImage,
        x: (canvas.width - filterWidth) / 2,
        y: (canvas.height - filterHeight) / 2,
        width: filterWidth,
        height: filterHeight
    });
    takeSnapshotButton.removeAttribute("disabled");
};

const addFilter = (filterElement) => {
    if (!isSnapshotTaken && imgLoaded) {
        const filterImage = new Image();
        filterImage.src = filterElement.src;
        if (filterImage.complete) {
            createFilter(filterImage);
        } else {
            filterImage.onload = () => createFilter(filterImage);
        }
    }
};

canvas.addEventListener("mousedown", function (event) {
    const offsetX = event.offsetX;
    const offsetY = event.offsetY;

    for (let i = filters.length - 1; i >= 0; i--) {
        if (offsetX >= filters[i].x && offsetX <= filters[i].x + filters[i].width &&
            offsetY >= filters[i].y && offsetY <= filters[i].y + filters[i].height) {
            filter = filters.splice(i, 1)[0];
            filters.push(filter);
            isDragging = true;
            isFilterClicked = true;
            prevX = offsetX;
            prevY = offsetY;
            break;
        }
    }
});

canvas.addEventListener("mousemove", function (event) {
    const offsetX = event.offsetX;
    const offsetY = event.offsetY;

    if (isDragging) {
        const dx = offsetX - prevX;
        const dy = offsetY - prevY;
        filter.x += dx;
        filter.y += dy;
        prevX = offsetX;
        prevY = offsetY;
    }
});

canvas.addEventListener("click", function (event) {
    const offsetX = event.offsetX;
    const offsetY = event.offsetY;

    for (let i = 0; i < filters.length; i++) {
        if (offsetX >= filters[i].x && offsetX <= filters[i].x + filters[i].width &&
            offsetY >= filters[i].y && offsetY <= filters[i].y + filters[i].height) {
            isFilterClicked = true;
            break;
        }
        isFilterClicked = false;
    }
});

canvas.addEventListener("mouseup", function (event) {
    isDragging = false;
});

canvas.addEventListener("touchstart", function (event) {
    event.preventDefault();
    for (let i = filters.length - 1; i >= 0; i--) {
        const touch = event.touches[0];
        const rect = canvas.getBoundingClientRect();
        const x = touch.clientX - rect.left;
        const y = touch.clientY - rect.top;
        if (x >= filters[i].x && x <= filters[i].x + filters[i].width &&
            y >= filters[i].y && y <= filters[i].y + filters[i].height) {
            filter = filters.splice(i, 1)[0];
            filters.push(filter);
            isDragging = true;
            isFilterClicked = true;
            prevX = x;
            prevY = y;
            break;
        }
    }
});

canvas.addEventListener("touchmove", function (event) {
    event.preventDefault();
    const touch = event.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    if (isDragging) {
        const dx = x - prevX;
        const dy = y - prevY;
        filter.x += dx;
        filter.y += dy;
        prevX = x;
        prevY = y;
    }
});

canvas.addEventListener("touchend", function (event) {
    event.preventDefault();
    isDragging = false;
});

canvas.addEventListener("touchstart", function (event) {
    event.preventDefault();
    for (let i = 0; i < filters.length; i++) {
        const touch = event.touches[0];
        const rect = canvas.getBoundingClientRect();
        const x = touch.clientX - rect.left;
        const y = touch.clientY - rect.top;
        if (x >= filters[i].x && x <= filters[i].x + filters[i].width &&
            y >= filters[i].y && y <= filters[i].y + filters[i].height) {
            isFilterClicked = true;
            break;
        }
        isFilterClicked = false;
    }
});

const draw = () => {
    ctx.save();
    ctx.scale(-1, 1);
    ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
    ctx.restore();
    images.forEach((image) => {
        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
    });

    ctx.drawImage(video, 0, 0, -canvas.width, canvas.height);
    filters.forEach((filter) => {
        if (filter && isFilterClicked) {
            drawFilterBorder();
        }
        ctx.drawImage(filter.image, filter.x, filter.y, filter.width, filter.height);
    });
    if (isFilterClicked) {
        deleteFilterButton.removeAttribute("disabled");
    } else {
        deleteFilterButton.setAttribute("disabled", "disabled");
    }
    requestAnimationFrame(draw);
}

const drawFilterBorder = () => {
    ctx.strokeStyle = 'red';
    ctx.lineWidth = 1;
    ctx.strokeRect(filter.x, filter.y, filter.width, filter.height);
}

const takeSnapshot = () => {
    if (isFilterClicked) {
        isFilterClicked = false;
        draw();
    }
    const image = new Image();
    image.src = canvas.toDataURL("image/png");
    images.push(image);
    deletePhotoButton.removeAttribute("disabled");
    filtersBackup = filters;
    imageBackup = image;
    filters = [];
    isSnapshotTaken = true;
    takeSnapshotDiv[0].classList.add("is-hidden");
    useImageButton.classList.remove("is-hidden");
}

const deleteFilter = () => {
    const index = filters.indexOf(filter);
    if (isFilterClicked && index > -1) {
        filters.splice(index, 1);
    }
    isFilterClicked = false;
    if (filters.length === 0) {
        deleteFilterButton.setAttribute("disabled", "disabled");
        takeSnapshotButton.setAttribute("disabled", "disabled");
    }
}

const deletePhoto = () => {
    if (filters)
        filters = [];
    images = [];
    deletePhotoButton.setAttribute("disabled", "disabled");
    if (input.files.length > 0) {
        input.value = "";
    }
    isSnapshotTaken = false;
    takeSnapshotButton.classList.remove("is-hidden");
    useImageButton.classList.add("is-hidden");
    takeSnapshotDiv[0].classList.remove("is-hidden");
}

const drawWithoutFilter = () => {
    ctx.save();
    ctx.scale(-1, 1);
    if (imageBackup)
        ctx.drawImage(imageBackup, canvas.width, 0, canvas.width, canvas.height);
    ctx.restore();

    imageBackup = canvas.toDataURL("image/png");
}

function dataURItoBlob(dataURI) {
    const byteString = atob(dataURI.split(',')[1]);
    const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], { type: mimeString });
}

const useImage = () => {
    var token = getCookie("token");

    if (token === "") {
        var phpSessionId = getCookie("PHPSESSID");
        if (phpSessionId !== "") {
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
        window.location.href = "/login";
        return;
    }

    createImageDiv[0].classList.add("is-hidden");
    uploadImageDiv[0].classList.remove("is-hidden");
    filters = [];
    isFilterClicked = false;
    drawWithoutFilter();
    const blob = dataURItoBlob(imageBackup);
    const formData = new FormData();
    formData.append("image", blob, 'canvas.png');
    formData.append("imageDimensions", JSON.stringify({ width: canvas.width, height: canvas.height }));
    formData.append("filters", JSON.stringify(filtersBackup));
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/studio-preview', true);
    xhr.onload = function () {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
            image.src = response.path;
        } else {
            console.log('Error:', xhr.status);
        }
    };
    xhr.onerror = function () {
        console.log('Request error:', xhr.status);
    };
    xhr.responseType = 'text';
    xhr.send(formData);
}

const deletePost = (post_id) => {
    var pictureItem = document.getElementById(`picture-${post_id}`);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/delete-post', true);
    var token = getCookie("token");

    if (token === "") {
        var phpSessionId = getCookie("PHPSESSID");
        if (phpSessionId !== "") {
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
        window.location.href = "/login";
        return;
    }

    var formData = new FormData();
    formData.append('pictureId', post_id);
    formData.append('token', token);

    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                pictureItem.remove();
                showSnackbar(response.message, "success");
            } else {
                showSnackbar(response.message, "danger");
            }
        } else {
            showSnackbar(response.message, "danger");
        }
    }
}
