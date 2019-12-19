<html>
    <head>
        <meta charset="utf-8" />
        <title>Reset Password</title>
    </head>

    <body>
        <header>
            <div class="header">
                <nav>
                <?php if (($_SESSION['user']) !== NULL): ?>
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
        <h2>Mot de passe oubliée ?</h2>
        <div>
            <form method='POST' action='#'>
                Nom d'utilisateur: <input type='text' name='login' value=''>
                <input type='submit' name='submit' value='OK'>
            </form>
        </div>
        <?php
        require_once '../models/user.class.php';
        if (!empty(htmlentities($_POST['login'])) and $_POST['submit'] === 'OK') {
            $login = trim($_POST['login']);
            $user = new User($login, '', '', '');
            $user->sendPassMail();
            if ($user->message)
                echo $user->message;
        }
        ?>
    </body>
</html>