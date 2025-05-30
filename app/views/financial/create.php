<?php include 'views/layout/header.php'; ?>

<div class="mb-8">
    <div class="flex items-center">
        <a href="index.php?page=financial" 
           class="mr-4 p-2 text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Transaksi</h1>
            <p class="text-gray-600 mt-2">Catat pemasukan atau pengeluaran dana qurban</p>
        </div>
    </div>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="card-gradient text-white p-6 rounded-t-xl">
            <h3 class="text-xl font-semibold flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Form Transaksi Baru
            </h3>
        </div>
        <div class="p-6">
            <?php if(isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="transaction_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Transaksi
                        </label>
                        <select id="transaction_type" 
                                name="transaction_type" 
                                required
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Pilih jenis transaksi</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori
                        </label>
                        <select id="category" 
                                name="category" 
                                required
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Pilih kategori</option>
                            <option value="iuran_qurban">Iuran Qurban</option>
                            <option value="pembelian_hewan">Pembelian Hewan</option>
                            <option value="perlengkapan">Perlengkapan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah (Rp)
                    </label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           step="0.01"
                           required
                           class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Masukkan jumlah dalam rupiah">
                </div>

                <div>
                    <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Transaksi
                    </label>
                    <input type="date" 
                           id="transaction_date" 
                           name="transaction_date" 
                           value="<?= date('Y-m-d') ?>"
                           required
                           class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              required
                              class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Jelaskan detail transaksi..."></textarea>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Transaksi
                    </button>
                    <a href="index.php?page=financial" 
                       class="flex-1 bg-gray-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<?php include 'views/layout/footer.php'; ?>