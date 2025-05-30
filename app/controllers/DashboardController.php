<?php
require_once 'models/User.php';
require_once 'models/FinancialTransaction.php';
require_once 'models/MeatDistribution.php';

class DashboardController {
    private $user;
    private $financial;
    private $meat_distribution;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->financial = new FinancialTransaction($this->db);
        $this->meat_distribution = new MeatDistribution($this->db);
    }

    public function index() {
        // Get user statistics
        $user_stats = $this->user->getTotalUsersByRole();
        
        // Get financial summary
        $financial_summary = $this->financial->getSummary();
        
        // Get meat distribution summary
        $distribution_summary = $this->meat_distribution->getDistributionSummary();
        
        // Get recent transactions
        $recent_transactions = array_slice($this->financial->getAll(), 0, 5);
        
        include 'views/dashboard/index.php';
    }
}
?>