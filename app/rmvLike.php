<?php
session_start() or die('Failed to resume session\n');
$id_photo = $_GET['id_photo'];
require_once '../models/like.class.php';
$pdo = new Like('id_photo', $_SESSION['user']);
$pdo->rmvLike();

?>