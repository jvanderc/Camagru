<?php

session_start() or die("Failed to resume session\n");
require_once '../models/photo.class.php';
$pdo = new Photo($_GET['id_photo'], '', $_SESSION['user']);
$pdo->deletePhoto();

?>