(function() {
    var width = 320;
    var height = 0;

    var streaming = false;

    var video = null;
    var canvas = null;
    var photo = null;
    var startbutton = null;

    var img1 = null;
    var img2 = null;
    var img3 = null;
    var selected = 0;

    var uploadbutton = null;
    var picture = null;

    var fusionbutton = null;
    var edit = null;

    function startup() {
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');
        photo = document.getElementById('photo');
        startbutton = document.getElementById('startbutton');

        img1 = document.getElementById('img1');
        img2 = document.getElementById('img2');
        img3 = document.getElementById('img3');

        uploadbutton = document.getElementById('uploadbutton');

        fusionbutton = document.getElementById('fusionbutton');

        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
        })
        .catch(function(err) {
            console.log("An error occurred: " + err);
        });

        video.addEventListener('canplay', function(ev){
            if (!streaming) {
                height = video.videoHeight / (video.videoWidth/width);
                video.setAttribute('width', width);
                video.setAttribute('height', height);
                canvas.setAttribute('width', width);
                canvas.setAttribute('height', height);
                streaming = true;
            }
        }, false);

        startbutton.addEventListener('click', function(ev){
            takePicture();
            ev.preventDefault();
        }, false);

        img1.addEventListener('click', function(ev) {
            selected = 1
            img1.setAttribute('style', 'background-color:#FFFF00');
            img2.setAttribute('style', 'background-color:#FFFFFF');
            img3.setAttribute('style', 'background-color:#FFFFFF');
            ev.preventDefault();
        }, false);
    
        img2.addEventListener('click', function(ev) {
            selected = 2;
            img1.setAttribute('style', 'background-color:#FFFFFF');
            img2.setAttribute('style', 'background-color:#FFFF00');
            img3.setAttribute('style', 'background-color:#FFFFFF');
            ev.preventDefault();
        }, false);
    
        img3.addEventListener('click', function(ev) {
            selected = 3;
            img1.setAttribute('style', 'background-color:#FFFFFF');
            img2.setAttribute('style', 'background-color:#FFFFFF');
            img3.setAttribute('style', 'background-color:#FFFF00');
            ev.preventDefault();
        }, false);

        uploadbutton.addEventListener('change', function(ev) {
            uploadPicture(this);
            ev.preventDefault();
        }, false);

        fusionbutton.addEventListener('click', function(ev) {
            fusionPicture();
            ev.preventDefault();
        }, false);

        savebutton.addEventListener('click', function(ev) {
            savePicture();
            ev.preventDefault();
        }, false);

    }

    function clearPhoto() {
        var context = canvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, canvas.width, canvas.height);
        var data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
    }

    function takePicture() {
        if (selected != 0) {
            var context = canvas.getContext('2d');
            if (width && height) {
                canvas.width = width;
                canvas.height = height;
                context.drawImage(video, 0, 0, width, height);
                picture = canvas.toDataURL('image/png');
                photo.setAttribute('src', picture);
            } else {
                clearPhoto();
            }
        } else {
            clearPhoto();
        }
    }

    function uploadPicture(input) {
        var file = input.files[0];
        var imageType = /image.*/;
        if (file.type.match(imageType) && file.size < 1500000) {
            var reader = new FileReader();
            reader.onload = function(e) {
                picture = e.target.result;
                photo.setAttribute('src', picture);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function fusionPicture() {
        if (selected != 0) {
            var data = picture.replace('data:image/png;base64,', '');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../app/mergePhoto.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('photo='+encodeURIComponent(data)+'&image='+selected);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var res = JSON.parse(xhr.responseText);
                    res = 'data:image/png;base64,'+res;
                    edit = res;
                    photo.setAttribute('src', edit);
                }
            }
        }
    }

    function savePicture() {
        var data = edit.replace('data:image/png;base64,', '');
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../app/savePhoto.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('photo='+encodeURIComponent(data));
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                clearPhoto();
                window.location = '../views/editing.php'
            }
        }
    }

    window.addEventListener('load', startup, false);
})();

function deletePicture(id_photo) {
    document.getElementById('delete_'+id_photo).parentNode.remove();
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../app/deletePhoto.php?id_photo='+id_photo, true);
    xhr.send();
}