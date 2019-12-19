<?php 
session_start() or die("Failed to resume session\n");
if ($_SESSION['user'] === null)
    header("Location: ../index.php");
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Webcam</title>
    </head>

    <body>
        <header>
            <div class="header">
                <nav>
                <?php if (($_SESSION['user']) !== ''): ?>
                    <a href='editing.php'>Webcam</a>
                    <a href='myGallery.php'>Ma Galerie</a>
                    <a href='gallery.php'>Galerie</a>
                    <a href='ModifyUser.php'>Modifier</a>
                    <a href='logout.php'>Deconnexion</a>
                <?php else: ?>
                    <a href='home.php'>Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
        </header>
        <video autoplay></video><br />
        <button>Prendre une photo</button>
        <button><img src="../public/img/image1.png"></button>
        <button><img src="../public/img/image2.png"></button>
        <button><img src="../public/img/image3.png"></button>
        <p>
            OU<br />
            Télécharger une image <br /><span>(jpeg, png | max 1.5 Mo)</span>
        </p>
        <label>
            <input type="file" accept="image/*" name="uploadpic" onchange="this.parentNode.setAttribute('title', this.value.replace(/^.*[\\/]/, ''))" />
        </label>
        <input type="submit" value="Fusionner les images" name="submit">
        <canvas></canvas><br />
        <button id="savebutton">Sauvgarder</button>
        <aside>
            <?php 
                require '../class/photos.class.php';
                $pic = new Pictures("", "", $_SESSION['user']);
                $res = $pic->getPicture();
                foreach ($res as $value): ?>
                    <img class="minipic" src="date:image/jpeg;base64, <?base64_encode($value['pic']) ?>"/>
                    <img class="deletepic" id="delete_<? $value['id_pic']  ?>" onclick="deletePicture(<?= $value['id_pic']  ?>)" src="../public/img/delete.png" />
                <?php endforeach; ?>
        </aside>
        <script type="application/javascript" src="../public/js/photo.js"></script>
    </body>
</html>