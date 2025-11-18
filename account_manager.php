<?php


class file_manager{
    private $path;
    private $pdo;
    private function create_database() {
        try {
            $pdo = new PDO('sqlite:' . $this->path);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Создание таблицы пользователей
            $sql = "CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                login TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )";
            
            $pdo->exec($sql);
            echo "База данных создана успешно";
            
        } catch(PDOException $e) {
            echo "Ошибка создания базы данных: " . $e->getMessage();
        }
    }

    function __construct($path){
        $this->path = $path;
        if (!file_exists($this->path)){
            $this->create_database();
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

    
    function set_to_file($login, $password){
        try{
            $stmp = $this->pdo->prepare("INSER INTO users(login, password) VALUES(?, ?)");
            return $stmp->excecute([$login, $password]);
        } catch(PDOException $ex){
            echo "Exception: " . $ex->getMessage();
            return false;
        }
        
    }
    private function get_to_file(){
        try{
            $stmp = $this->pdo->prepare("SELECT * FROM users");
            $stmp->execute();
            return $stmp->featchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex){
            echo "Exception" . $ex->getMessage();
            return false;
        }
    }

    function get_user_by_login_password($login, $password){
        try{
            $stmp = $this->pdo->prepare("SELECT * FROM users WHERE login = ? AND password = ?");
            $stmp->execute([$login, $password]);
            return $stmp->featch(PDO::FETCH_ASSOC);
        } catch(PDOException $ex){
            echo "Exception" . $ex->getMessage();
            return false;
        }
    }

}
?>