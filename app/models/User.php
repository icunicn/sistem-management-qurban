<?php
require_once 'config/config.php';

class User {
    private $conn;
    private $table_name = "users";
    private $db;

    public $user_id;
    public $warga_id;
    public $username;
    public $password;
    public $role;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
        $this->db = new Database();
    }

    public function login($username, $password) {
        $query = "SELECT u.*, w.nama_lengkap, w.nik
                  FROM " . $this->table_name . " u 
                  LEFT JOIN warga w ON u.warga_id = w.warga_id 
                  WHERE u.username = :username LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    public function register($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET warga_id=:warga_id, username=:username, password=:password, 
                      role=:role, email=:email";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":warga_id", $data['warga_id']);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":password", password_hash($data['password'], PASSWORD_DEFAULT));
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":email", $data['email']);

        return $stmt->execute();
    }

    public function getUsersByRole($role) {
        $query = "SELECT u.*, w.nama_lengkap, w.nik, w.rt, w.rw 
                  FROM " . $this->table_name . " u 
                  LEFT JOIN warga w ON u.warga_id = w.warga_id 
                  WHERE u.role = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $role);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalUsersByRole() {
        $query = "SELECT role, COUNT(*) as total FROM " . $this->table_name . " GROUP BY role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['role']] = $row['total'];
        }
        return $result;
    }
}
?>