<?php 
session_start() or die("Failed to resume session\n");
if ($_SESSION['user'] === null)
    header("Location: ../index.php");
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Webcam</title>
        <link rel='stylesheet' href='../public/css/editing.css' type='text/css' media='all'>
        <script src='../public/js/photo.js'></script>
    </head>

    <body>
        <header>
            <div class="header">
                <nav>
                <?php if (($_SESSION['user']) !== ''): ?>
                    <a href='gallery.php'>Galerie</a>
                    <a href='modifyUser.php'>Modifier</a>
                    <a href='logout.php'>Deconnexion</a>
                <?php else: ?>
                    <a href='home.php'>Connexion</a>
                <?php endif; ?>
                </nav>
            </div>
        </header>
        <div class="webcam">
            <div class="camera">
                <video id="video">Video stream not available</video><br />
                <button id="img1"><img src="../public/img/image1.png" width=100></button>
                <button id="img2"><img src="../public/img/image2.png" width=100></button>
                <button id="img3"><img src="../public/img/image3.png" width=100></button>
                <button id="startbutton">Prendre une photo</button>
            </div>
            <canvas id="canvas">
            </canvas>
            <div class="output">
                <img id='photo' alt='The screen captur will appear in this box.'>
            </div>
            <p>
                OU<br />
                Télécharger une image <br /><span>(jpeg, png | max 1.5 Mo)</span>
            </p>
            <label class="file">
                <input type="file" accept="image/*" name="image" id="uploadbutton" />
            </label>
            <button id="fusionbutton">Fusionner images</button>
            <button id="savebutton">Sauver photo</button>
        </div>
        <aside id="side">
            <?php
            require_once '../models/photo.class.php';
            $photo = new Photo('', '', $_SESSION['user']);
            $res = $photo->getPhoto();
            foreach ($res as $value): ?>
                <div class="display">
                    <img class="mygallery" src="data:image/jpeg;base64, <?= base64_encode($value['photo']); ?>"/>
                    <button id="delete_<?= $value['id_photo'] ?>" onclick="deletePicture(<?= $value['id_photo'] ?>)">Supprimer</button>
                </div>
            <?php endforeach; ?>
        </aside>
    </body>
</html>