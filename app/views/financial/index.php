<?php include 'views/layout/header.php'; ?>

<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-money-bill-wave mr-3 text-green-600"></i>
                Manajemen Keuangan
            </h1>
            <p class="text-gray-600 mt-2">Kelola pemasukan dan pengeluaran dana qurban</p>
        </div>
        <a href="index.php?page=financial&action=create" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Transaksi
        </a>
    </div>
</div>

<!-- Financial Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pemasukan</p>
                <p class="text-3xl font-bold text-green-600">Rp <?= number_format($summary['pemasukan'] ?? 0, 0, ',', '.') ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-arrow-up text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                <p class="text-3xl font-bold text-red-600">Rp <?= number_format($summary['pengeluaran'] ?? 0, 0, ',', '.') ?></p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-arrow-down text-2xl text-red-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Saldo</p>
                <p class="text-3xl font-bold text-blue-600">Rp <?= number_format($summary['saldo'] ?? 0, 0, ',', '.') ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-wallet text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="card-gradient text-white p-6 rounded-t-xl">
        <h3 class="text-xl font-semibold flex items-center">
            <i class="fas fa-list mr-2"></i>
            Daftar Transaksi
        </h3>
    </div>
    <div class="p-6">
        <?php if(!empty($transactions)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Tanggal</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Jenis</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Kategori</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Deskripsi</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Dibuat Oleh</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-700">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $transaction): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    <?= date('d/m/Y', strtotime($transaction['transaction_date'])) ?>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-3 py-1 text-xs font-medium rounded-full <?= $transaction['transaction_type'] === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($transaction['transaction_type']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    <?= ucfirst(str_replace('_', ' ', $transaction['category'])) ?>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    <?= $transaction['description'] ?>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    <?= $transaction['nama_lengkap'] ?? $transaction['username'] ?>
                                </td>
                                <td class="py-4 px-4 text-sm text-right font-medium <?= $transaction['transaction_type'] === 'pemasukan' ? 'text-green-600' : 'text-red-600' ?>">
                                    Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada transaksi</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan transaksi pertama</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<?php include 'views/layout/footer.php'; ?>