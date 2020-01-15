<?php

session_start() or die('Failed to start session\n');
require_once '../models/user.class.php';

?>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Camagru</title>
    </head>
        
    <body>
        <h1>Camagru</h1>
        <header>
            <div class="header">
                <a href='gallery.php'>Galerie</a>
            </div>
        </header>
        <div id='user'>
            <h2>Connexion</h2>
            <div id='signin'>
                <form method='POST' action='#'>
                    Nom d'utilisateur: <input type='text' name='login' value=''>
                    Mot de passe: <input type='password' name='passwd' value=''>
                    <input type='submit' name='subin' value='OK'>
                </form>
                <a href='reset.php'>Mot de passe oublié ?</a>
            </div>
            <?php
            if (!empty(htmlentities($_POST['login'])) and !empty(htmlentities($_POST['passwd'])) and $_POST['subin'] === 'OK') {
                $login = trim(htmlentities($_POST['login']));
                $user = new User($login, htmlentities($_POST['passwd']), '', '');
                $user->connectUser();
                if ($user->message) {
                    echo $user->message;
                }
                else {
                    $_SESSION['user'] = $login;
                    echo '<script> location.replace("../index.php"); </script>';
                }
            }
            ?>
            <h3>Inscription<h3>
            <div id='signup'>
                <form method='POST' action='#'>
                    Nom d'utilisteur: <input type='text' name='newlog' value=''>
                    Mot de passe: <input type='password' name='newpass' value=''>
                    E-mail: <input type='text' name='email' value=''>
                    <input type='submit' name='subup' value='OK'>
                </form>
            </div>
            <?php
            if (!empty(htmlentities($_POST['newlog'])) and !empty(htmlentities($_POST['newpass'])) and !empty(htmlentities($_POST['email'])) and $_POST['subup'] === 'OK') {
                $newlog = trim($_POST['newlog']);
                $email = trim($_POST['email']);
                $user = new User($newlog, $_POST['newpass'], $email, '');
                $user->sendConfirmationEmail();
                if ($user->message)
                    echo $user->message;
            }
            else if ($_POST['subup'] === 'OK')
                echo 'Remplir tous les champs';
            else if ($_GET['t'] != '') {
                $token = $_GET['t'];
                $user = new User('', '', '', $token);
                $user->confirmUser();
                if ($user->message)
                    echo $user->message;
            }
            ?>
        </div>
    </body>
</html>