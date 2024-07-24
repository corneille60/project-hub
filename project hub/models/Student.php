<?php
class Student {
    private $conn;
    private $table = 'student';

    public $StdRegNumber;
    public $StdFname;
    public $StdLname;
    public $StdGender;
    public $StdEmail;
    public $StdPhoneNumber;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get student details by registration number
    public function getStudentByRegNumber($regNumber) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE StdRegNumber = :StdRegNumber LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':StdRegNumber', $regNumber);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Register a new student
    public function registerStudent() {
        $query = 'INSERT INTO ' . $this->table . ' SET StdRegNumber = :StdRegNumber, StdFname = :StdFname, StdLname = :StdLname, StdGender = :StdGender, StdEmail = :StdEmail, StdPhoneNumber = :StdPhoneNumber';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':StdRegNumber', $this->StdRegNumber);
        $stmt->bindParam(':StdFname', $this->StdFname);
        $stmt->bindParam(':StdLname', $this->StdLname);
        $stmt->bindParam(':StdGender', $this->StdGender);
        $stmt->bindParam(':StdEmail', $this->StdEmail);
        $stmt->bindParam(':StdPhoneNumber', $this->StdPhoneNumber);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update student details
    public function updateStudent($regNumber) {
        $query = 'UPDATE ' . $this->table . ' SET StdFname = :StdFname, StdLname = :StdLname, StdGender = :StdGender, StdEmail = :StdEmail, StdPhoneNumber = :StdPhoneNumber WHERE StdRegNumber = :StdRegNumber';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':StdRegNumber', $regNumber);
        $stmt->bindParam(':StdFname', $this->StdFname);
        $stmt->bindParam(':StdLname', $this->StdLname);
        $stmt->bindParam(':StdGender', $this->StdGender);
        $stmt->bindParam(':StdEmail', $this->StdEmail);
        $stmt->bindParam(':StdPhoneNumber', $this->StdPhoneNumber);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete student
    public function deleteStudent($regNumber) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE StdRegNumber = :StdRegNumber';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':StdRegNumber', $regNumber);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
