let modal;
let modalImg;
let captionText;
let photoId;
let photoDir = "../upload_photos_normal/";

// kui leht laetakse
window.onload = function () {
    modal = document.getElementById("modalarea");
    modalImg = document.getElementById("modalimg");
    captionText = document.getElementById("modalcaption");
    //lisame kõigile thumbidele kliki kuualaja
    let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
    let(i = 0; i < allThumbs.length; i++) {
        allThumbs[i].addEventListener("click", openModal);
    }
    document.getElementsById("modalclose").addEventListener("click", closeModal);
}

function openModal(e) {
    modalImg.src = photoDir + e.target.dataset.fn; //see fn oli meil endal html-i pandud, nüüd siin kasutan
}

function closeModal() {

}