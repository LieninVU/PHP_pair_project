<?php


class users_manager{
    private $path;
    private $pdo;
    private function create_database() {
        try {
            
            $this->pdo = new PDO('sqlite:' . $this->path);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Создание таблицы пользователей
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                login TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )";
            
            $this->pdo->exec($sql);
            echo "База данных создана успешно";
            
        } catch(PDOException $e) {
            echo "Ошибка создания базы данных: " . $e->getMessage();
        }
    }

    function __construct($path){
        $this->path = $path;
        $db_is_new = !file_exists($this->path);
        $this->pdo = new PDO('sqlite:' . $this->path);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($db_is_new) {
            $this->create_database();
        } else {
            // дополнительная проверка — если таблицы "users" нет, создаём
            $res = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
            if(!$res->fetch()) {
                $this->create_database();
            }
        }
    }
    function is_file_exist($path): bool{
        if(file_exists($path)){
            return true;
        }
        else{
            return false;
        }
    }

    
    function add_user($login, $password){
        try{
            $stmp = $this->pdo->prepare("INSERT INTO users(login, password) VALUES(?, ?)");
            return $stmp->execute([$login, $password]);
        } catch(PDOException $ex){
            echo "Exception: " . $ex->getMessage();
            return false;
        }
        
    }
    private function get_user(){
        try{
            $stmp = $this->pdo->prepare("SELECT * FROM users");
            $stmp->execute();
            return $stmp->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex){
            echo "Exception" . $ex->getMessage();
            return false;
        }
    }

    function get_user_by_login_password($login, $password){
        if($this->is_empty()){
            return false;
        }
        try{
            $stmp = $this->pdo->prepare("SELECT * FROM users WHERE login = ? AND password = ?");
            $stmp->execute([$login, $password]);
            return $stmp->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex){
            echo "Exception" . $ex->getMessage();
            return false;
        }
    }



    function is_empty(){
        try{
            $stmp = $this->pdo->query("SELECT * FROM users");
            $count = $stmp->fetchColumn();
            if($count >0){
                return false;
            }
            else{
                return true;
            }
        }
        catch(PDOException $ex){
            echo "". $ex->getMessage();
            return true;
        }
    }
}
?>