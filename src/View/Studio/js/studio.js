const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
const video = document.getElementById("video");
const images = [];
const filters = [];
let filter = null;
let isDragging = false;
let isFilterClicked = false;
let prevX = null;
let prevY = null;

video.addEventListener('loadedmetadata', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
});

const createFilter = (filterImage) => {
    const canvasRatio = canvas.width / canvas.height;
    const filterRatio = filterImage.width / filterImage.height;
    const [filterWidth, filterHeight] = canvasRatio > filterRatio
        ? [canvas.width / 4, canvas.width / 4 / filterRatio]
        : [canvas.height / 4 * filterRatio, canvas.height / 4];
    filters.push({
        image: filterImage,
        x: (canvas.width - filterWidth) / 2,
        y: (canvas.height - filterHeight) / 2,
        width: filterWidth,
        height: filterHeight
    });
};

const addFilter = (filterElement) => {
    const filterImage = new Image();
    filterImage.src = filterElement.src;
    if (filterImage.complete) {
        createFilter(filterImage);
    } else {
        filterImage.onload = () => createFilter(filterImage);
    }
};

canvas.addEventListener("mousedown", function (event) {
    for (let i = 0; i < filters.length; i++) {
        if (event.offsetX >= filters[i].x && event.offsetX <= filters[i].x + filters[i].width &&
            event.offsetY >= filters[i].y && event.offsetY <= filters[i].y + filters[i].height) {
            filter = filters[i];
            isDragging = true;
            isFilterClicked = true;
            prevX = event.offsetX;
            prevY = event.offsetY;
            break;
        }
    }
});

canvas.addEventListener("mousemove", function (event) {
    if (isDragging) {
        const dx = event.offsetX - prevX;
        const dy = event.offsetY - prevY;
        filter.x += dx;
        filter.y += dy;
        prevX = event.offsetX;
        prevY = event.offsetY;
    }
});

canvas.addEventListener("mouseup", function (event) {
    isDragging = false;
});

canvas.addEventListener("click", function (event) {
    for (let i = 0; i < filters.length; i++) {
        if (event.offsetX >= filters[i].x && event.offsetX <= filters[i].x + filters[i].width &&
            event.offsetY >= filters[i].y && event.offsetY <= filters[i].y + filters[i].height) {
            isFilterClicked = true;
            break;
        }
        isFilterClicked = false;
    }
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
            filter = filters[i];
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
        if (touch.clientX >= filters[i].x && touch.clientX <= filters[i].x + filters[i].width &&
            touch.clientY >= filters[i].y && touch.clientY <= filters[i].y + filters[i].height) {
            isFilterClicked = true;
            break;
        }
        isFilterClicked = false;
    }
});

navigator.mediaDevices.getUserMedia({ video: true })
    .then(function (stream) {
        const video = document.getElementById("video");
        video.srcObject = stream;
        video.play();

        requestAnimationFrame(draw);
    })
    .catch(function (err) {
        console.log("An error occurred: " + err);
    });

function draw() {
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
    requestAnimationFrame(draw);
}

function drawFilterBorder() {
    ctx.strokeStyle = 'red';
    ctx.lineWidth = 1;
    ctx.strokeRect(filter.x, filter.y, filter.width, filter.height);
}

function takeSnapshot() {
    if (isFilterClicked) {
        isFilterClicked = false;
        draw();
    }
    const image = new Image();
    image.src = canvas.toDataURL("image/png");
    images.push(image);
}