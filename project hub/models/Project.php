<?php
class Project {
    private $conn;
    private $table = 'project';

    public $ProjectCode;
    public $ProjectName;
    public $ProjectProblems;
    public $ProjectSolutions;
    public $ProjectAbstract;
    public $ProjectDissertation;
    public $ProjectSourceCodes;
    public $DepartmentCode;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function submit($reg_number) {
        $query = 'INSERT INTO ' . $this->table . ' SET ProjectCode = UUID(), ProjectName = :ProjectName, ProjectProblems = :ProjectProblems, ProjectSolutions = :ProjectSolutions, ProjectAbstract = :ProjectAbstract, DepartmentCode = :DepartmentCode';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':ProjectName', $this->ProjectName);
        $stmt->bindParam(':ProjectProblems', $this->ProjectProblems);
        $stmt->bindParam(':ProjectSolutions', $this->ProjectSolutions);
        $stmt->bindParam(':ProjectAbstract', $this->ProjectAbstract);
        $stmt->bindParam(':DepartmentCode', $this->DepartmentCode);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getProjectsByStudent($reg_number) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE StdRegNumber = :reg_number';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reg_number', $reg_number);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompletedProjects() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ProjectDissertation != "" AND ProjectSourceCodes != ""';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchCompletedProjects($search) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ProjectName LIKE :search AND Status = "Completed"';
        $stmt = $this->conn->prepare($query);
        $search = '%' . $search . '%';
        $stmt->bindParam(':search', $search);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectById($id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ProjectCode = :id LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProject($id) {
        $query = 'UPDATE ' . $this->table . ' SET ProjectName = :ProjectName, ProjectProblems = :ProjectProblems, ProjectSolutions = :ProjectSolutions, ProjectAbstract = :ProjectAbstract WHERE ProjectCode = :id';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ProjectName', $this->ProjectName);
        $stmt->bindParam(':ProjectProblems', $this->ProjectProblems);
        $stmt->bindParam(':ProjectSolutions', $this->ProjectSolutions);
        $stmt->bindParam(':ProjectAbstract', $this->ProjectAbstract);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deleteProject($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ProjectCode = :id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
