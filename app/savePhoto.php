<?php

session_start() or die("Failed to resume session\n");
$photo = base64_decode($_POST['photo']);
require_once '../models/photo.class.php';
$pdo = new Photo('', $photo, $_SESSION['user']);
$pdo->addPhoto();

?>