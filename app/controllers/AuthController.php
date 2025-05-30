<?php
require_once 'models/User.php';
require_once 'models/Warga.php';

class AuthController extends Controller {
    private $user;
    private $warga;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->warga = new Warga($this->db);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            
            if (!$username || !$password) {
                $error = "Username dan password harus diisi!";
                include 'views/auth/login.php';
                return;
            }
            
            $user_data = $this->user->login($username, $password);
            
            if ($user_data) {
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['role'] = $user_data['role'];
                $_SESSION['nama_lengkap'] = $user_data['nama_lengkap'];
                $_SESSION['warga_id'] = $user_data['warga_id'];
                
                header("Location: index.php?page=dashboard");
                exit();
            } else {
                $error = "Username atau password salah!";
                include 'views/auth/login.php';
            }
        } else {
            include 'views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $warga_data = [
                'nik' => filter_input(INPUT_POST, 'nik', FILTER_SANITIZE_STRING),
                'nama_lengkap' => filter_input(INPUT_POST, 'nama_lengkap', FILTER_SANITIZE_STRING),
                'jenis_kelamin' => filter_input(INPUT_POST, 'jenis_kelamin', FILTER_SANITIZE_STRING),
                'alamat' => filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING),
                'rt' => filter_input(INPUT_POST, 'rt', FILTER_SANITIZE_STRING),
                'rw' => filter_input(INPUT_POST, 'rw', FILTER_SANITIZE_STRING),
                'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING)
            ];
            
            $warga_id = $this->warga->create($warga_data);
            
            if ($warga_id) {
                $user_data = [
                    'warga_id' => $warga_id,
                    'username' => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING),
                    'password' => filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING),
                    'role' => filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING),
                    'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)
                ];
                
                if ($this->user->register($user_data)) {
                    header("Location: index.php?page=login&success=1");
                    exit();
                } else {
                    $error = "Gagal membuat akun user!";
                }
            } else {
                $error = "Gagal menyimpan data warga!";
            }
            
            include 'views/auth/register.php';
        } else {
            include 'views/auth/register.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}
?>