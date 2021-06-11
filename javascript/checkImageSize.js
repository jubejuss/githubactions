let fileSizeLimit = 1 * 1024 * 1024;

window.onload = function () {
    document.getElementById("Photo_submit").disabled = true;
    document.getElementById("file_input").addEventListener("change", checkSize);
}

function checkSize() {

}