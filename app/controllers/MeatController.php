<?php
require_once 'models/MeatDistribution.php';
require_once 'models/User.php';

class MeatController {
    private $meat_distribution;
    private $user;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->meat_distribution = new MeatDistribution($this->db);
        $this->user = new User($this->db);
    }

    public function index() {
        $distributions = $this->meat_distribution->getAll();
        $summary = $this->meat_distribution->getDistributionSummary();
        
        include 'views/meat/index.php';
    }

    public function generate() {
        if($_POST) {
            $allocations = [
                'warga' => $_POST['warga_amount'],
                'panitia' => $_POST['panitia_amount'],
                'berqurban' => $_POST['berqurban_amount']
            ];
            
            if($this->meat_distribution->generateDistribution($allocations)) {
                header("Location: index.php?page=meat&success=1");
                exit();
            } else {
                $error = "Gagal generate distribusi daging!";
            }
        }
        
        $user_stats = $this->user->getTotalUsersByRole();
        include 'views/meat/generate.php';
    }

    public function pickup() {
        if($_POST) {
            $distribution_id = $_POST['distribution_id'];
            $status = $_POST['status'];
            
            if($this->meat_distribution->updateStatus($distribution_id, $status)) {
                header("Location: index.php?page=meat&pickup_success=1");
                exit();
            }
        }
        
        header("Location: index.php?page=meat");
        exit();
    }

    public function card() {
        $user_id = $_SESSION['user_id'];
        $distribution = $this->meat_distribution->getByUserId($user_id);
        
        if(!$distribution) {
            header("Location: index.php?page=dashboard&error=no_distribution");
            exit();
        }
        
        // Generate QR Code data
        $qr_data = json_encode([
            'distribution_id' => $distribution['distribution_id'],
            'user_id' => $distribution['user_id'],
            'nama' => $distribution['nama_lengkap'],
            'amount' => $distribution['amount'],
            'status' => $distribution['status']
        ]);
        
        include 'views/meat/card.php';
    }
}
?>