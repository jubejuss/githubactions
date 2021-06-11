let fileSizeLimit = 1 * 1024 * 1024;

window.onload = function () {
    document.getElementById("Photo_submit").disabled = true;
    document.getElementById("file_input").addEventListener("change", checkSize);
}

function checkSize() {
    if (document.getElementById("file_input").files[0].size <= fileSizeLimit) {
        document.getElementById("Photo_submit").disabled = false;
    } else {
        document.getElementById("Photo_submit").disabled = true;
    }
}