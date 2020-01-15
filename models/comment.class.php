<?php

class Comment {
    private $pdo;
    private $id_photo;
    private $login;
    private $comment;

    public function __construct($id_photo, $login, $comment) {
        try {
            require '../config/database.php';
            $this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->id_photo = $id_photo;
            $this->login = $login;
            $this->comment = $comment;
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function getComment() {
        try {
            $req = $this->pdo->prepare("SELECT * FROM `Comments` WHERE `id_photo` = ?");
            $req->execute(array($this->id_photo));
            return $req->fetch(PDO::FETCH_ASSSOC);
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    function addComment() {
        try {
            date_default_timezone_set('Europe/Bruxelles');
            $creatin_date = date('Y-m-d H:i:s');
            $req = $this->pdo->prepare("INSERT INTO `Comments` (`id_photo`, `comment`, `login`, `creation_date`) VALUES (?, ?, ?, ?)");
            $req->execute(array($this->id_photo, $this->comment, $this->login, $this->creation_date));
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function rmvAllComment() {
        try {
            $req = $this->pdo->prepare("DELETE FROM `Comments` WHERE `id_photo` = ?");
            $req->execute(array($this->id_photo));
        }
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}