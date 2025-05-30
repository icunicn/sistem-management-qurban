<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Qurban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f4ff',
                            100: '#e0e7ff',
                            500: '#667eea',
                            600: '#5a67d8',
                            700: '#4c51bf',
                            800: '#434190',
                            900: '#3c366b'
                        },
                        secondary: {
                            500: '#764ba2',
                            600: '#6a4190',
                            700: '#5e377e'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <nav class="w-64 sidebar-gradient shadow-lg">
            <div class="p-6">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white flex items-center justify-center">
                        <i class="fas fa-mosque mr-2"></i> Qurban System
                    </h2>
                    <p class="text-white/70 text-sm mt-2">Selamat datang, <?= $_SESSION['nama_lengkap'] ?? 'User' ?></p>
                    <span class="inline-block bg-white/20 text-white text-xs px-3 py-1 rounded-full mt-2">
                        <?= ucfirst($_SESSION['role'] ?? '') ?>
                    </span>
                </div>
                
                <ul class="space-y-2">
                    <li>
                        <a href="index.php?page=dashboard" 
                           class="flex items-center px-4 py-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 <?= ($_GET['page'] ?? '') === 'dashboard' ? 'bg-white/20 text-white' : '' ?>">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'panitia'): ?>
                    <li>
                        <a href="index.php?page=financial" 
                           class="flex items-center px-4 py-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 <?= ($_GET['page'] ?? '') === 'financial' ? 'bg-white/20 text-white' : '' ?>">
                            <i class="fas fa-money-bill-wave mr-3"></i>
                            Keuangan
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li>
                        <a href="index.php?page=meat" 
                           class="flex items-center px-4 py-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 <?= ($_GET['page'] ?? '') === 'meat' ? 'bg-white/20 text-white' : '' ?>">
                            <i class="fas fa-cut mr-3"></i>
                            Distribusi Daging
                        </a>
                    </li>
                    
                    <?php if($_SESSION['role'] === 'warga' || $_SESSION['role'] === 'berqurban'): ?>
                    <li>
                        <a href="index.php?page=meat&action=card" 
                           class="flex items-center px-4 py-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200">
                            <i class="fas fa-qrcode mr-3"></i>
                            Kartu QR
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'panitia'): ?>
                    <li>
                        <a href="index.php?page=qr&action=scanner" 
                           class="flex items-center px-4 py-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200 <?= ($_GET['page'] ?? '') === 'qr' ? 'bg-white/20 text-white' : '' ?>">
                            <i class="fas fa-camera mr-3"></i>
                            Scanner QR
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="mt-8 pt-8 border-t border-white/20">
                    <a href="index.php?page=logout" 
                       class="flex items-center px-4 py-3 text-red-200 hover:text-red-100 hover:bg-red-500/20 rounded-lg transition-all duration-200">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-6">