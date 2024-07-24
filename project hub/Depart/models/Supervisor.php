<?php
class Supervisor {
    private $conn;
    private $table = 'supervisor';

    public $SupervisorEmail;
    public $SupervisorFname;
    public $SupervisorLname;
    public $SupervisorPhoneNumber;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllSupervisors() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
