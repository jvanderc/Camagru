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
        <h2>Modifier mot de passe</h2>
        <div>
            <form method='POST' action='#'>
                Mot de passe: <input type='password' name='passwd' value=''>
                <input type='submit' name='submit' value='OK'>
            </form>
        </div>
        <?php
        require_once '../models/user.class.php';
        if (htmlentities($_GET['t']) != '' and !empty(htmlentities($_POST['passwd'])) and $_POST['submit'] === 'OK') {
            $token = htmlentities($_GET['t']);
            $user = new User('', $_POST['passwd'], '', $token);
            $user->resetPassword();
            if ($user->message)
                echo $user->message;
        }
        ?>
    </body>
</html>