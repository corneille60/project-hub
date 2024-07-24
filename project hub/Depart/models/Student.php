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

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (StdRegNumber, StdFname, StdLname, StdGender, StdEmail, StdPhoneNumber) VALUES (:StdRegNumber, :StdFname, :StdLname, :StdGender, :StdEmail, :StdPhoneNumber)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':StdRegNumber', $this->StdRegNumber);
        $stmt->bindParam(':StdFname', $this->StdFname);
        $stmt->bindParam(':StdLname', $this->StdLname);
        $stmt->bindParam(':StdGender', $this->StdGender);
        $stmt->bindParam(':StdEmail', $this->StdEmail);
        $stmt->bindParam(':StdPhoneNumber', $this->StdPhoneNumber);
        return $stmt->execute();
    }
}
?>
