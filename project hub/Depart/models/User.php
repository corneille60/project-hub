<?php
class User {
    private $conn;
    private $table = 'admins';

    public $id;
    public $role;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE username = :username';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        // $stmt->bindParam(':role', $role);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($this->password == $row['password']) {
                $this->id = $row['id'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }
}
?>
