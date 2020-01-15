<?php

session_start() or die('Failed to start session\n');

if (!isset($_SESSION['user'])) {
    require_once 'config/setup.php';
    header('Location: views/home.php');
}
else {
    header('Location: views/editing.php');
}