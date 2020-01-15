<?php

session_start() or die('Failed to start session\n');
require_once '../models/user.class.php';

?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Reset Password</title>
    </head>

    <body>
        <header>
            <div class="header">
                <nav>
                <?php if (($_SESSION['user']) !== ''): ?>
                    <a href='editing.php'>Webcam</a>
                    <a href='gallery.php'>Galerie</a>
                    <a href='logout.php'>Deconnexion</a>
                <?php else: ?>
                    <a href='home.php'>Connexion</a>
                <?php endif; ?>
                </nav>
            </div>
        </header>
        <h2>Modifier</h2>
        <div>
            <form method='POST' action='#'>
                Nom d'utilisateur: <input type='text' name='login' value=''>
                Mot de passe: <input type='password' name='passwd' value=''>
                E-mail: <input type='text' name='email' value=''>
                <input type='submit' name='submit' value='OK'>
            </form>
        </div>
        <?php
        if (!empty(htmlentities($_POST['login'])) and $_POST['submit'] === 'OK') {
            $login = trim($_POST['login']);
            $user = new User($login, '', '', '');
            $user->modifyLogin();
            $_SESSION['user'] = $login;
            if ($user->message)
                echo $user->message;
        }
        if (!empty(htmlentities($_POST['passwd'])) and $_POST['submit'] === 'OK') {
            $user = new User('', htmlentities($_POST['passwd']), '', '');
            $user->modifyPasswd();
            if ($user->message)
                echo $user->message;
        }
        if (!empty(htmlentities($_POST['email'])) and $_POST['submit'] === 'OK') {
            $user = new User('', '', htmlentities($_POST['email']), '');
            $user->modifyMail();
            if ($user->message)
                echo $user->message;
        }
        ?>
    </body>
</html>