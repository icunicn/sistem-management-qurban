<?php
require_once 'models/FinancialTransaction.php';

class FinancialController {
    private $financial;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->financial = new FinancialTransaction($this->db);
    }

    public function index() {
        $transactions = $this->financial->getAll();
        $summary = $this->financial->getSummary();
        $by_category = $this->financial->getByCategory();
        
        include 'views/financial/index.php';
    }

    public function create() {
        if($_POST) {
            $data = [
                'transaction_type' => $_POST['transaction_type'],
                'amount' => $_POST['amount'],
                'description' => $_POST['description'],
                'category' => $_POST['category'],
                'transaction_date' => $_POST['transaction_date'],
                'created_by' => $_SESSION['user_id']
            ];
            
            if($this->financial->create($data)) {
                header("Location: index.php?page=financial&success=1");
                exit();
            } else {
                $error = "Gagal menyimpan transaksi!";
            }
        }
        
        include 'views/financial/create.php';
    }
}
?>  