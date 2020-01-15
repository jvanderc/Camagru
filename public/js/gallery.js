function addLike(id_photo) {
    var elem = document.getElementById('nblike_'+id_photo);
    var nb = elem.innerHTML;
    nb = parseInt(nb);
    nb++;
    elem.innerHTML = nb+' j\' aime';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../app/addLike.php?id_photo='+id_photo, true);
    xhr.send();
}

function rmvLike(id_photo) {
    var elem = document.getElementById('nblike_'+id_photo);
    var nb = elem.innerHTML;
    nb = parseInt(nb);
    nb--;
    elem.innerHTML = nb+' j\' aime';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../app/rmvLike.php?id_photo='+id_photo, true);
    xhr.send();
}

function addComment(id_photo, comment, login) {
    com = htmlEntities(comment.value);
    if (com.trim() === "")
        return ;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../app/addComment.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('id_photo='+id_photo+'&comment='+com);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.Done && xhr.status === 200) {
            var div = document.createElement('DIV');
            div.setAttribute('class', 'comments');
            div.innerHTML = '<b>'+login+'</b>'+com;
            document.getElementById('firstcomment_'+id).appendChild(div);
            comment.value = '';
        }
    }
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}