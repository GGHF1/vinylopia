let currentSlideIndex = 0;
let currentGallery = [];

function openModal(vinylId) {
    const gallery = document.getElementById(`gallery-${vinylId}`);
    currentGallery = Array.from(gallery.getElementsByTagName('img'));
    currentSlideIndex = 0;
    showSlide(currentSlideIndex);
    populateThumbnails();
    document.getElementById('myModal').style.display = "block";
    updateThumbnailSelection(currentSlideIndex);
    checkOverflow();
}

function closeModal() {
    document.getElementById('myModal').style.display = "none";
}

function changeSlide(n) {
    currentSlideIndex += n;

    if (currentSlideIndex >= currentGallery.length) { // checker to avoid conflict with swipe buttons and thumbnails
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = currentGallery.length - 1;
    }

    showSlide(currentSlideIndex);
}

function showSlide(index) {
    const modalImg = document.getElementById('modal-img');
    modalImg.src = currentGallery[index].src;
    updateThumbnailSelection(index);
}

function populateThumbnails() {
    const thumbnailsContainer = document.getElementById('thumbnails-container');
    thumbnailsContainer.innerHTML = ''; 

    currentGallery.forEach((img, index) => {
        const thumbnail = document.createElement('img');
        thumbnail.src = img.src;
        thumbnail.classList.add('thumbnail');
        thumbnail.onclick = () => {
            currentSlideIndex = index;  
            showSlide(currentSlideIndex);
        };
        thumbnailsContainer.appendChild(thumbnail);
    });
}

function updateThumbnailSelection(index) {
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach((thumbnail, i) => {
        if (i === index) {
            thumbnail.classList.add('selected');
        } else {
            thumbnail.classList.remove('selected');
        }
    });
}

window.onclick = function(event) {
    if (event.target == document.getElementById('myModal')) {
        document.getElementById('myModal').style.display = "none";
    }
}

function checkOverflow() {
    const thumbnailsContainer = document.querySelector('.thumbnails-container');
    const isOverflowing = thumbnailsContainer.scrollWidth > thumbnailsContainer.clientWidth;
    if (isOverflowing) {
        thumbnailsContainer.classList.add('scrolled');
    } else {
        thumbnailsContainer.classList.remove('scrolled');
    }
}

window.addEventListener('resize', checkOverflow);