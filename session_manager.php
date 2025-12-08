<?php
class session_manager{
    private $path;
    private $pdo;

    function __construct(){
        $this->path = 'session.db';
        if (!file_exists($this->path)){
            $this->create_database();
        }
        $this->connect_to_database();
    }

    private function connect_to_database() {
        try {
            $this->pdo = new PDO('sqlite:' . $this->path);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $result = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' LIMIT 1");
            if($result && $result->fetch(PDO::FETCH_ASSOC) !== false){
                $this->create_database();
            }
        } catch(PDOException $e) {
            echo "Ошибка подключения: " . $e->getMessage();
        }
    }

    private function create_database() {
        try {
            $pdo = new PDO('sqlite:' . $this->path);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "CREATE TABLE IF NOT EXISTS sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL UNIQUE,
                user_id INTEGER NOT NULL,
                expires_at DATETIME NOT NULL
            )";
            
            $pdo->exec($sql);
        } catch(PDOException $e) {
            echo "Ошибка создания таблицы сессий: " . $e->getMessage();
        }
    }

    public function create_session($user_id) {
        try {
            $expires_at = time() + 3600;
            $session_id = session_create_id();
            $stmt = $this->pdo->prepare("INSERT INTO sessions (session_id, user_id, expires_at) VALUES (?, ?, ?)");
            return $stmt->execute([$session_id, $user_id, $expires_at]);
        } catch(PDOException $e) {
            echo "Ошибка создания сессии: " . $e->getMessage();
            return false;
        }
    }

    public function validate_session($session_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT user_id FROM sessions WHERE session_id = ? AND expires_at > ?");
            $stmt->execute([$session_id, time()]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result){
                $stmp = $this->pdo->prepare("UPDATE sessions SET excires_at = ? WHERE session_id = ?");
                $stmp->execute([time(), $session_id]);
                return true;
            }
        } catch(PDOException $e) {
            echo "Ошибка проверки сессии: " . $e->getMessage();
            return false;
        }
    }

    public function delete_session($session_id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE session_id = ?");
            return $stmt->execute([$session_id]);
        } catch(PDOException $e) {
            echo "Ошибка удаления сессии: " . $e->getMessage();
            return false;
        }
    }

    public function cleanup_expired_sessions() {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE expires_at <= CURRENT_TIMESTAMP");
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Ошибка очистки сессий: " . $e->getMessage();
            return false;
        }
    }
}
?>