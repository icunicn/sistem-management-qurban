<?php
require_once 'config/database.php';

class MeatDistribution {
    private $conn;
    private $table_name = "meat_distribution";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET user_id=:user_id, amount=:amount, status=:status";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $data['user_id']);
        $stmt->bindParam(":amount", $data['amount']);
        $stmt->bindParam(":status", $data['status']);

        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT md.*, u.username, u.role, w.nama_lengkap, w.nik, w.rt, w.rw 
                  FROM " . $this->table_name . " md 
                  LEFT JOIN users u ON md.user_id = u.user_id 
                  LEFT JOIN warga w ON u.warga_id = w.warga_id 
                  ORDER BY w.nama_lengkap";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $query = "SELECT md.*, u.username, u.role, w.nama_lengkap, w.nik, w.rt, w.rw 
                  FROM " . $this->table_name . " md 
                  LEFT JOIN users u ON md.user_id = u.user_id 
                  LEFT JOIN warga w ON u.warga_id = w.warga_id 
                  WHERE md.user_id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($distribution_id, $status) {
        $query = "UPDATE " . $this->table_name . " 
                  SET status=:status, pickup_date=:pickup_date 
                  WHERE distribution_id=:distribution_id";
        
        $stmt = $this->conn->prepare($query);
        
        $pickup_date = ($status === 'sudah_diambil') ? date('Y-m-d H:i:s') : null;
        
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":pickup_date", $pickup_date);
        $stmt->bindParam(":distribution_id", $distribution_id);

        return $stmt->execute();
    }

    public function generateDistribution($allocations) {
        // Clear existing distributions
        $query = "DELETE FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Generate new distributions based on allocations
        foreach($allocations as $role => $amount) {
            $query = "INSERT INTO " . $this->table_name . " (user_id, amount, status)
                      SELECT user_id, :amount, 'belum_diambil' 
                      FROM users WHERE role = :role";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":role", $role);
            $stmt->execute();
        }
        return true;
    }

    public function getDistributionSummary() {
        $query = "SELECT 
                    u.role,
                    COUNT(*) as total_people,
                    SUM(md.amount) as total_meat,
                    SUM(CASE WHEN md.status = 'sudah_diambil' THEN 1 ELSE 0 END) as picked_up,
                    SUM(CASE WHEN md.status = 'sudah_diambil' THEN md.amount ELSE 0 END) as meat_picked_up
                  FROM " . $this->table_name . " md 
                  LEFT JOIN users u ON md.user_id = u.user_id 
                  GROUP BY u.role";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>