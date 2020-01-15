; <?php

class Like {
    private $pdo;
    private $id_photo;
    private $login;

    public function __construct($id_photo, $login) {
        try {
            require '../config/database.php';
            $this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->id_photo = $id_photo;
            $this->login = $login;
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function getLike() {
        try {
            $req = $this->pdo->prepare("SELECT * FROM `likes` WHERE `id_photo` = ? AND `login` = ?");
            $req->execute(array($this->id_photo, $this->login));
            return $req->fetch(PDO::FETCH_ASSSOC);
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function nbLike() {
        try {
            $req = $this->pdo->query("SELECT count(*) FROM `likes` WHERE `id_photo` = $this->id_photo");
            $nblike = $req->fetch(PDO::FETCH_ASSOC);
            return $nblike['count(*)'];
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function addLike() {
        try {
            date_default_timezone_set('Europe/Bruxelles');
            $creation_date = date('Y-m-d H:i:s');
            $req = $this->pdo->prepare("INSERT INTO `likes` (`id_photo`, `login`, `creation_date`) VALUES (?, ?, ?)");
            $req->execute(array($this->id_photo, $this->login, $creation_date));
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function rmvLike() {
        try {
            $req = $this->pdo->prepare("DELETE FROM `likes` WHERE 'id_photo' = ? AND 'login' = ?");
            $req->execute(array($this->id_photo, $this->login));
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function rmvAllLike() {
        try {
            $req = $this->pdo->prepare("DELETE FROM `likes` WHERE 'id_photo' = ?");
            $req->execute(array($this->id_photo));
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}