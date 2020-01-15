<?php

class Photo {
    private $pdo;
    private $id_photo;
    private $photo;
    private $login;

    public function __construct($id_photo, $photo, $login) {
        try {
            require '../config/database.php';
            $this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->id_photo = $id_photo;
            $this->photo= $photo;
            $this->login = $login;
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function getPhoto() {
        try {
            $req = $this->pdo->prepare("SELECT * FROM `photos` WHERE `login` = ? ORDER BY `creation_date` DESC LIMIT 20");
            $req->execute(array($this->login));
            $photo = $req->fetchAll(PDO::FETCH_ASSOC);
            return $photo;
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function addPhoto() {
        try {
            date_default_timezone_set('Europe/Bruxelles');
            $creation_date = date("Y-m-d H:i:s");
            $req = $this->pdo->prepare("INSERT INTO `photos` (`login`, `creation_date`, `photo`) VALUES (?, ?, ?)");
            $req->execute(array($this->login, $creation_date, $this->photo));
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function getPhotoByPage($page, $nbphotobypage) {
        try {
            $req = $this->pdo->prepare("SELECT * FROM `photos` ORDER BY `creation_date` DESC LIMIT " . $page . ", " . $nbphotobypage);
            $req->execute();
            $photo = $req->fetchAll(PDO::FETCH_ASSOC);
            return $photo;
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function nbPhotos() {
        try {
            $req = $this->pdo->query("SELECT count(*) FROM `photos`");
            $nbPhoto = $req->fetch(PDO::FETCH_ASSOC);
            return $nbPhoto['count(*)'];
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function nbPhotosByLogin() {
        try {
            $req = $this->pdo->query("SELECT count(*) FROM `photos` WHERE `login` = '" . $this->login . "'");
            $nbPhoto = $req->fetch(PDO::FETCH_ASSOC);
            return $nbPhoto['countn(*)'];
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function deletePhoto() {
        try {
            $req = $this->pdo->prepare("DELETE FROM `photos` WHERE `id_photo` = ? AND `login` = ?");
            $req->execute(array($this->id_photo, $this->login));
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}