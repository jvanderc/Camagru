<?php

class User {
    private $pdo;
    private $login;
    private $passwd;
    private $email;
    private $token;
    public $message;

    public function __construct($login, $passwd, $email, $token) {
        try {
            require_once '../config/database.php';
            $this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->login = $login;
            $this->passwd = $passwd;
            $this->email = $email;
            $this->token = $token;
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    private function checkUser() {
        try {
            $req = $this->pdo->prepare('SELECT * FROM `users` WHERE `login` = ?');
            $req->execute(array($this->login));
            $user = $req->fetch(PDO::FETCH_ASSOC);
            return $user;
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    private function checkPassword() {
        if (strlen($this->passwd) > 255)
            return $this->message = 'Password excède 255 charctère';
        if (!preg_match('/(?=.*[0-9])(?=.*[A-Za-z]).{6,30}/', $this->passwd))
            return $this->message = 'Password doit faire au moins 6 char dont 1 lettre et 1 chiffre';
    }

    private function checkNewUser() {
        if (strlen($this->login) > 255)
            return $this->message = 'Login excède 255 charctère';
        $user = $this->checkUser();
        if ($user)
            return $this->message = 'Login déjà pris';
        self::checkPassword();
        if ($this->message)
            return ;
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
            return $this->message = 'Email pas valide';
    }

    public function sendConfirmationEmail() {
        self::checkNewUser();
        if ($this->message)
            return ;
        $token = uniqid(rand(), true);
        $url = 'localhost:8080/views/home.php?t=' . $token;
        date_default_timezone_set('Belgique/Bruxelles');
        $creation_date = date('Y-m-d H:i:s');
        $token_expiration_date = date('Y-m-d H:i:s', strtotime($creation_date . ' + 1 day'));
        try {
            $req = $this->pdo->prepare('INSERT INTO `users` (`login`, `passwd`, `email`, `creation_date`, `token`, `token_expiration_date`) VALUES (?, ?, ?, ?, ?, ?)');
            $req->execute(array($this->login, hash('whirlpool', $this->passwd), $this->email, $creation_date, $token, $token_expiration_date));
            $req = $this->pdo->prepare('DELETE FROM `users` WHERE `token_expiration_date` < NOW() AND `confirmation` = 0');
            $req->execute();
            require_once '../app/mail.php';
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function confirmUser() {
        try {
            $req = $this->pdo->prepare('SELECT * FROM `users` WHERE `token` = ?');
            $req->execute(array($this->token));
            $user = $req->fetch(PDO::FETCH_ASSOC);
            if (!$user)
                return $this->message = 'token déjà validé ou expiré';
            $req = $this->pdo->prepare('UPDATE `users` SET `confirmation` = ?, `token` = ?, `token_expiration_date` = ? WHERE `token` = ?');
            $req->execute(array(1, NULL, NULL, $this->token));
            $this->message = 'Email Confirmée, Bienvenue';
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function connectUser() {
        try {
            $user = $this->checkUser();
            if (!$user)
                return $this->message = 'Login inexistant.';
            else if ($user['confirmation'] == 0)
                return $this->message = 'Compte pas validé. Consultez vos mails';
            else if ($user['passwd'] != hash('whirlpool', $this->passwd))
                return $this->message = 'Mot de passe incorrect.';
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function sendPassMail() {
        try {
            $user = $this->checkUser();
            if (!$user)
                return $this->message = 'Login inexistant.';
            $email = $user['email'];
            $token = uniqid(rand(), true);
            $url = 'localhost:8080/views/resetPassword.php?t=' . $token;
            date_default_timezone_set('Belgique/Bruxelles');
            $creation_date = date('Y-m-d H:i:s');
            $token_expiration_date = date('Y-m-d H:i:s', strtotime($creation_date . ' + 1 day'));
            $req = $this->pdo->prepare('UPDATE `users` SET `token` = ?, `token_expiration_date` = ? WHERE `login` = ?');
            $req->execute(array($token, $token_expiration_date, $this->login));
            $req = $this->pdo->prepare('UPDATE`users` SET `token` = ?, `token_expiration_date` = ? WHERE `token_expiration_date` < NOW() AND `confirmation` = 0');
            $req->execute(array(NULL, NULL));
            require_once '../app/mailPasswd.php';
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function resetPassword() {
        try {
            $req = $this->pdo->prepare('SELECT * FROM `users` WHERE `token` = ?');
            $req->execute(array($this->token));
            $user = $req->fetch(PDO::FETCH_ASSOC);
            if (!$user)
                return $this->message = 'lien expire ou pas valide.';
            self::checkPassword();
            if ($this->message)
                return $this->message;
            $req = $this->pdo->prepare('UPDATE `users` SET `passwd` = ? WHERE `token` = ?');
            $req->execute(array(hash('whirlpool', $this->passwd), $this->token));
            $this->message = 'mot de passe modifié';
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function modifyLogin() {
        try {
            if (strlen($this->login) > 255)
                return $this->message = 'Login excède 255 charctère';
            $user = $this->checkUser();
            if ($user)
                return $this->message = 'Login déjà pris';
            $req = $this->pdo->prepare('UPDATE `users` SET `login` = ? WHERE `login` = ?');
            $req->execute(array($this->login, $_SESSION['user']));
            $this->message = "Nom d'utilisateur modifié.";
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function modifyPasswd() {
        try {
            self::checkPassword();
            if ($this->message)
                return ;
            $req = $this->pdo->prepare('UPDATE `users` SET `passwd` = ? WHERE `login` = ?');
            $req->execute(array(hash('whirlpool', $this->passwd), $_SESSION['user']));
            $this->message = "Mot de passe modifié.";
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function modifyMail() {
        try {
            if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
                return $this->message = 'Email pas valide';
            $req = $this->pdo->prepare('UPDATE `users` SET `email` = ? WHERE `login` = ?');
            $req->execute(array($this->email, $_SESSION['user']));
            $this->message = "Mail modifié.";
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}