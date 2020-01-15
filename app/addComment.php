<?php
session_start() or die('Failed to resume session\n');
$comment = $_POST['comment'];
$id_photo = $_POST['id_photo'];
require_once '../models/comment.class.php';
if ($comment !== null) {
    $pdo = new COmment('id_photo', $_SESSION['user'], $comment);
    $pdo->addComment();
}

?>