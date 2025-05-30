<?php
require_once 'lib/phpqrcode/qrlib.php';

// Generate QR code if not exists
$qr_data = json_encode([
    'distribution_id' => $distribution['distribution_id'],
    'user_id' => $distribution['user_id'],
    'nama' => $distribution['nama_lengkap'],
    'nik' => $distribution['nik'],
    'amount' => $distribution['amount'],
    'status' => $distribution['status'],
    'role' => $distribution['role'],
    'timestamp' => time()
]);

// Create QR code directory if not exists
if (!file_exists('assets/qr_codes/')) {
    mkdir('assets/qr_codes/', 0777, true);
}

$qr_filename = 'assets/qr_codes/qr_' . $distribution['distribution_id'] . '.png';

// Generate QR code
QRcode::png($qr_data, $qr_filename, QRcode::QR_ECLEVEL_M, 8, 2);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pengambilan Daging Qurban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .qr-card, .qr-card * {
                visibility: visible;
            }
            .qr-card {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                width: 400px !important;
                max-width: none !important;
                box-shadow: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Navigation -->
        <div class="flex justify-center space-x-4 mb-8 no-print">
            <a href="index.php?page=dashboard" 
               class="inline-flex items-center px-6 py-3 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg shadow-sm hover:bg-green-700 transition-colors">
                <i class="fas fa-print mr-2"></i>
                Cetak Kartu
            </button>
            <a href="<?= $qr_filename ?>" 
               download="kartu-qurban-<?= $distribution['nama_lengkap'] ?>.png" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>
                Download QR
            </a>
        </div>

        <!-- QR Card -->
        <div class="qr-card max-w-md mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden border-4 border-blue-200">
            <!-- Header -->
            <div class="card-gradient text-white p-6 text-center relative">
                <div class="absolute top-0 left-0 w-full h-full bg-black/10"></div>
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-2">
                        <i class="fas fa-mosque mr-2"></i>
                        KARTU PENGAMBILAN
                    </h2>
                    <h3 class="text-xl font-semibold">DAGING QURBAN 1446H</h3>
                    <p class="text-sm mt-2 opacity-90">RT <?= $distribution['rt'] ?>/RW <?= $distribution['rw'] ?></p>
                </div>
            </div>
            
            <!-- Personal Info -->
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 font-medium">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800"><?= $distribution['nama_lengkap'] ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">NIK</p>
                        <p class="font-semibold text-gray-800"><?= $distribution['nik'] ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Role</p>
                        <p class="font-semibold text-gray-800"><?= ucfirst($distribution['role']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">Jumlah Daging</p>
                        <p class="font-semibold text-gray-800"><?= number_format($distribution['amount'], 1) ?> kg</p>
                    </div>
                </div>
                
                <div class="flex justify-center">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-medium <?= $distribution['status'] === 'sudah_diambil' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= $distribution['status'] === 'sudah_diambil' ? 'Sudah Diambil' : 'Belum Diambil' ?>
                    </span>
                </div>
            </div>

            <!-- QR Code -->
            <div class="p-6 bg-gray-50 text-center">
                <div class="bg-white p-4 rounded-xl shadow-sm inline-block">
                    <img src="<?= $qr_filename ?>" alt="QR Code" class="w-48 h-48 mx-auto">
                </div>
                <div class="mt-4">
                    <p class="font-semibold text-gray-800">Tunjukkan QR Code ini kepada panitia</p>
                    <p class="text-sm text-gray-600">untuk verifikasi pengambilan daging</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-100 p-4 text-center text-xs text-gray-600 space-y-1">
                <p>ID Distribusi: #<?= str_pad($distribution['distribution_id'], 6, '0', STR_PAD_LEFT) ?></p>
                <p>Dicetak: <?= date('d/m/Y H:i:s') ?> WIB</p>
                <?php if($distribution['status'] === 'sudah_diambil' && $distribution['pickup_date']): ?>
                    <p>Diambil: <?= date('d/m/Y H:i', strtotime($distribution['pickup_date'])) ?> WIB</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Instructions -->
        <div class="max-w-4xl mx-auto mt-12 no-print">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="card-gradient text-white p-6 rounded-t-xl">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Petunjuk Penggunaan Kartu
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-semibold text-blue-600 mb-4">Untuk Penerima:</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="bg-green-100 p-2 rounded-full mr-3 mt-1">
                                        <i class="fas fa-print text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Cetak atau download kartu</p>
                                        <p class="text-sm text-gray-600">Simpan kartu ini dengan baik</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-blue-100 p-2 rounded-full mr-3 mt-1">
                                        <i class="fas fa-id-card text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Bawa kartu saat pengambilan</p>
                                        <p class="text-sm text-gray-600">Pastikan kartu dalam kondisi baik</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-yellow-100 p-2 rounded-full mr-3 mt-1">
                                        <i class="fas fa-qrcode text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Tunjukkan QR Code</p>
                                        <p class="text-sm text-gray-600">Panitia akan scan untuk verifikasi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-semibold text-purple-600 mb-4">Untuk Panitia:</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="bg-purple-100 p-2 rounded-full mr-3 mt-1">
                                        <i class="fas fa-camera text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Scan QR Code</p>
                                        <p class="text-sm text-gray-600">Gunakan aplikasi scanner</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-green-100 p-2 rounded-full mr-3 mt-1">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Verifikasi data</p>
                                        <p class="text-sm text-gray-600">Pastikan data sesuai</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-red-100 p-2 rounded-full mr-3 mt-1">
                                        <i class="fas fa-hand-holding text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Serahkan daging</p>
                                        <p class="text-sm text-gray-600">Sesuai jumlah yang tertera</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                            <div>
                                <h5 class="font-semibold text-yellow-800 mb-2">Penting untuk diperhatikan:</h5>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li>• Kartu ini hanya berlaku untuk satu kali pengambilan</li>
                                    <li>• QR Code mengandung data terenkripsi untuk keamanan</li>
                                    <li>• Jika kartu hilang, segera hubungi panitia</li>
                                    <li>• Pengambilan hanya dapat dilakukan oleh pemilik kartu</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php if($_SESSION['role'] === 'panitia' || $_SESSION['role'] === 'admin'): ?>
                    <div class="mt-6 text-center">
                        <a href="index.php?page=qr&action=scanner" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-qrcode mr-2"></i>
                            Buka Scanner QR
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>