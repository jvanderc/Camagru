<?php

session_start() or die('Failed to start session\n');

if (session_destroy())
    header('Location: ../index.php');