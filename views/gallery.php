<?php 
session_start() or die("Failed to resume session\n");
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Gallery</title>
        <link rel='stylesheet' href='../public/css/gallery.css' type='text/css' media='all'>
        <script src='../public/js/gallery.js'></script>
    </head>

    <body>
        <header>
            <div class="header">
                <nav>
                <?php if (($_SESSION['user']) !== NULL): ?>
                    <a href='editing.php'>Webcam</a>
                    <a href='modifyUser.php'>Modifier</a>
                    <a href='logout.php'>Deconnexion</a>
                <?php else: ?>
                    <a href='home.php'>Connexion</a>
                <?php endif; ?>
                </nav>
            </div>
        </header>
        <div class="gallery">
            <?php
            require_once '../models/photo.class.php';
            $photo = new Photo('', '', '');
            $page = isset($_GET['page']) ? htmlentities($_GET['page']) : 1;
            $nbphoto = $photo->nbPhoto();
            $nbpage = ceil($nbphoto / 5);
            if ($nbphoto == 0): ?>
                <p>Prenez la première photo de Camagru sur la page webcam!</p>
            <?php
            elseif ($page > $nbpage || preg_match('/^[0-9]*$/', $page) == 0):
                 echo '<script> location.replace("gallery.php?page=1") </script>';
            else:
                $photos = $photo->getPhotoByPage((($page - 1) * 5), 5);
                require_once '../models/like.class.php';
                require_once '../models/comment.class.php';
                foreach ($photos as $value):
                    $id_photo = $value['id_photo'];
                    $user = $_SESSION['user'];
                    $like = new Like($id_photo, $user);
                    $liked = $like->getLike();
                    $nblike = $like->nbLike();
                    $comment = new Commment($id_photo, '', '');
                    $comments = $comment->getComment(); ?>
                    <div class="photogallery">
                        <div class="login" id="login_<?= $id_photo ?>"><?= $value['login'] ?></div>
                        <img class="photo" id="photo_<?= $id_photo ?>" src="data:image/jpeg;base64, <?= base64_encode($value['photo']) ?>"/>
                        <div class="likecomment">
                            <?php
                            if ($_SESSION['user'] !== null):
                                if ($liked === false): ?>
                                    <button onclick="addLike(<?= $id_photo ?>)" class="like">like</button>
                                <?php else: ?>
                                    <button onclick="rmvLike(<?= $id_photo ?>)" class="like">dislike</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="like">like</button>
                            <?php endif; ?>
                            <label for="newcomment_<?= $id_photo ?>" class="comment">Nouveaux Commentaire</label>
                            <span class="nblike" id="nblike_<?= $id_photo ?>"><?= $nblike ?> likes</span>
                        </div>
                        <div id="firstcomment_<?= $id_photo ?>">
                            <?php
                            foreach ($comments as $v): ?>
                                <div class="comments"><b><?= $v['login'] ?></b> <?= $v['comment'] ?></div>
                            <?php endforeach; ?>
                        </div>
                        <form method="post">
                            <?php
                            if ($_SESSION['user'] !== null): ?>
                                <input type="text" maxlength="255" onkeypress="{if (event.keyCode == 13) { event.preventDefault(); addComment('<?= $id_photo ?>', this, '<?= $user ?>')}}"
                                class="inputcomment" id="new_comment_<?= $id_photo ?>" name="new_comment_<?= $id_photo ?>" placeholder="Ecrire un commentaire...">
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endforeach; ?>
            <div class="pages">
                <?php
                if ($page != 1): ?>
                    <a href="gallery.php?page=<?= ($page - 1) ?>" class="prev">Precedent</a>
                <?php endif; ?>
                <span class="pagenb"><b><?= $page ?></b></span>
                <?php
                if ($page != $nbpage): ?>
                    <a href="gallery.php?page=<?= ($page - 1) ?>" class="next">Suivant</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </body>
</html>