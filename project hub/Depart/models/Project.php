<?php
class Project {
    private $conn;
    private $table = 'project';

    public $ProjectCode;
    public $ProjectName;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getSubmittedProjects() {
        $query = 'SELECT * FROM ' . $this->table ;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignToSupervisor($project_code, $supervisor_email) {
        $query = 'UPDATE ' . $this->table . ' SET SupervisorEmail = :SupervisorEmail, Status = "assigned" WHERE ProjectCode = :ProjectCode';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':SupervisorEmail', $supervisor_email);
        $stmt->bindParam(':ProjectCode', $project_code);
        return $stmt->execute();
    }
}
?>
