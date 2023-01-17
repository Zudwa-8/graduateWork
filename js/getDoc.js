function getDoc(id) {
    var formData = new FormData();
    formData.append("id", id);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/php/GetDoc.php", false);
    xhr.send(formData);
};