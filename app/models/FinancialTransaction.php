<?php
require_once 'config/database.php';

class FinancialTransaction {
    private $conn;
    private $table_name = "financial_transactions";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET transaction_type=:transaction_type, amount=:amount, description=:description, 
                      category=:category, transaction_date=:transaction_date, created_by=:created_by";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":transaction_type", $data['transaction_type']);
        $stmt->bindParam(":amount", $data['amount']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":category", $data['category']);
        $stmt->bindParam(":transaction_date", $data['transaction_date']);
        $stmt->bindParam(":created_by", $data['created_by']);

        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT ft.*, u.username, w.nama_lengkap 
                  FROM " . $this->table_name . " ft 
                  LEFT JOIN users u ON ft.created_by = u.user_id 
                  LEFT JOIN warga w ON u.warga_id = w.warga_id 
                  ORDER BY ft.transaction_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummary() {
        $query = "SELECT 
                    transaction_type,
                    SUM(amount) as total_amount,
                    COUNT(*) as total_transactions
                  FROM " . $this->table_name . " 
                  GROUP BY transaction_type";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = ['pemasukan' => 0, 'pengeluaran' => 0];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['transaction_type']] = $row['total_amount'];
        }
        
        $result['saldo'] = $result['pemasukan'] - $result['pengeluaran'];
        return $result;
    }

    public function getByCategory() {
        $query = "SELECT 
                    category,
                    transaction_type,
                    SUM(amount) as total_amount
                  FROM " . $this->table_name . " 
                  GROUP BY category, transaction_type 
                  ORDER BY category";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>