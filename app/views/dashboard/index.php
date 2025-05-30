<?php include 'views/layout/header.php'; ?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
        Dashboard
    </h1>
    <p class="text-gray-600 mt-2">Selamat datang di Sistem Manajemen Qurban</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Warga</p>
                <p class="text-3xl font-bold text-blue-600"><?= $user_stats['warga'] ?? 0 ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-users text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Yang Berqurban</p>
                <p class="text-3xl font-bold text-green-600"><?= $user_stats['berqurban'] ?? 0 ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-hand-holding-heart text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Panitia</p>
                <p class="text-3xl font-bold text-purple-600"><?= $user_stats['panitia'] ?? 0 ?></p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-user-tie text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Saldo</p>
                <p class="text-2xl font-bold text-yellow-600">Rp <?= number_format($financial_summary['saldo'] ?? 0, 0, ',', '.') ?></p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-wallet text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Financial Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="card-gradient text-white p-6 rounded-t-xl">
            <h3 class="text-xl font-semibold flex items-center">
                <i class="fas fa-chart-pie mr-2"></i>
                Ringkasan Keuangan
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-sm font-medium text-green-600">Pemasukan</p>
                    <p class="text-2xl font-bold text-green-700">Rp <?= number_format($financial_summary['pemasukan'] ?? 0, 0, ',', '.') ?></p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-red-600">Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-700">Rp <?= number_format($financial_summary['pengeluaran'] ?? 0, 0, ',', '.') ?></p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-blue-600">Saldo</p>
                    <p class="text-2xl font-bold text-blue-700">Rp <?= number_format($financial_summary['saldo'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Distribution Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="card-gradient text-white p-6 rounded-t-xl">
            <h3 class="text-xl font-semibold flex items-center">
                <i class="fas fa-cut mr-2"></i>
                Status Distribusi Daging
            </h3>
        </div>
        <div class="p-6">
            <?php if(!empty($distribution_summary)): ?>
                <div class="space-y-4">
                    <?php foreach($distribution_summary as $dist): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-3"></span>
                                <span class="font-medium text-gray-700"><?= ucfirst($dist['role']) ?></span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900"><?= $dist['picked_up'] ?>/<?= $dist['total_people'] ?> diambil</p>
                                <p class="text-xs text-gray-500"><?= number_format($dist['meat_picked_up'], 1) ?> kg</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada distribusi daging</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'panitia'): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="card-gradient text-white p-6 rounded-t-xl">
        <h3 class="text-xl font-semibold flex items-center">
            <i class="fas fa-history mr-2"></i>
            Transaksi Terbaru
        </h3>
    </div>
    <div class="p-6">
        <?php if(!empty($recent_transactions)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Tanggal</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Jenis</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Kategori</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Deskripsi</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-700">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_transactions as $transaction): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    <?= date('d/m/Y', strtotime($transaction['transaction_date'])) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full <?= $transaction['transaction_type'] === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($transaction['transaction_type']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    <?= ucfirst(str_replace('_', ' ', $transaction['category'])) ?>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    <?= $transaction['description'] ?>
                                </td>
                                <td class="py-3 px-4 text-sm text-right font-medium <?= $transaction['transaction_type'] === 'pemasukan' ? 'text-green-600' : 'text-red-600' ?>">
                                    Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 text-center">
                <a href="index.php?page=financial" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Lihat Semua Transaksi
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada transaksi</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>