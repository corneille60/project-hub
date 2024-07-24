<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $reg_number;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        $query = 'INSERT INTO ' . $this->table . ' SET reg_number = :reg_number, password = :password, role = :role';

        $stmt = $this->conn->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(':reg_number', $this->reg_number);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function login() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE reg_number = :reg_number LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':reg_number', $this->reg_number);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->role = $row['role'];

                return true;
            }
        }

        return false;
    }
}
?>
