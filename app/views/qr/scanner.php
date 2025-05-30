<?php include 'views/layout/header.php'; ?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-qrcode mr-3 text-blue-600"></i>
        Scanner QR Code
    </h1>
    <p class="text-gray-600 mt-2">Scan QR Code untuk verifikasi pengambilan daging qurban</p>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="card-gradient text-white p-6 rounded-t-xl">
            <h3 class="text-xl font-semibold flex items-center">
                <i class="fas fa-camera mr-2"></i>
                Scan QR Code Pengambilan Daging
            </h3>
        </div>
        <div class="p-6">
            <!-- Camera Scanner -->
            <div class="text-center mb-6">
                <div class="relative inline-block">
                    <video id="video" 
                           class="w-full max-w-md h-64 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg object-cover"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <div id="scanner-overlay" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-lg">
                        <div class="text-white text-center">
                            <i class="fas fa-camera text-4xl mb-2"></i>
                            <p>Klik "Mulai Scan" untuk mengaktifkan kamera</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <button id="start-scan" 
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-play mr-2"></i>
                    Mulai Scan
                </button>
                <button id="stop-scan" 
                        disabled
                        class="w-full bg-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-700 transition-colors flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-stop mr-2"></i>
                    Stop Scan
                </button>
            </div>

            <!-- Manual Input -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Atau Input Manual QR Data:</h4>
                <form id="manual-form" class="space-y-4">
                    <div>
                        <label for="qr-data-input" class="block text-sm font-medium text-gray-700 mb-2">
                            Data QR Code
                        </label>
                        <textarea id="qr-data-input" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Paste data QR code di sini..."></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-check mr-2"></i>
                        Verifikasi Data
                    </button>
                </form>
            </div>

            <!-- Result Display -->
            <div id="scan-result" class="mt-6 hidden">
                <div id="result-alert" class="p-4 rounded-lg">
                    <div id="result-content"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Scans -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8">
        <div class="card-gradient text-white p-6 rounded-t-xl">
            <h3 class="text-xl font-semibold flex items-center">
                <i class="fas fa-history mr-2"></i>
                Scan Terakhir
            </h3>
        </div>
        <div class="p-6">
            <div id="recent-scans">
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada scan hari ini</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let context = canvas.getContext('2d');
let scanning = false;
let stream = null;

document.getElementById('start-scan').addEventListener('click', startScanning);
document.getElementById('stop-scan').addEventListener('click', stopScanning);
document.getElementById('manual-form').addEventListener('submit', handleManualInput);

async function startScanning() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'environment',
                width: { ideal: 640 },
                height: { ideal: 480 }
            } 
        });
        
        video.srcObject = stream;
        video.play();
        scanning = true;
        
        document.getElementById('start-scan').disabled = true;
        document.getElementById('stop-scan').disabled = false;
        document.getElementById('scanner-overlay').classList.add('hidden');
        
        // Start scanning loop
        requestAnimationFrame(scanQRCode);
        
    } catch (err) {
        console.error('Error accessing camera:', err);
        showResult('error', 'Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera.');
    }
}

function stopScanning() {
    scanning = false;
    
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    video.srcObject = null;
    
    document.getElementById('start-scan').disabled = false;
    document.getElementById('stop-scan').disabled = true;
    document.getElementById('scanner-overlay').classList.remove('hidden');
}

function scanQRCode() {
    if (!scanning) return;
    
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);
        
        if (code) {
            console.log('QR Code detected:', code.data);
            processQRData(code.data);
            stopScanning();
            return;
        }
    }
    
    requestAnimationFrame(scanQRCode);
}

function handleManualInput(e) {
    e.preventDefault();
    const qrData = document.getElementById('qr-data-input').value.trim();
    
    if (qrData) {
        processQRData(qrData);
    } else {
        showResult('error', 'Silakan masukkan data QR code');
    }
}

async function processQRData(qrData) {
    try {
        // Validate JSON
        const data = JSON.parse(qrData);
        
        if (!data.distribution_id) {
            throw new Error('Data QR tidak valid');
        }
        
        // Send to server for verification
        const response = await fetch('index.php?page=qr&action=scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'qr_data=' + encodeURIComponent(qrData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showResult('success', result.message, result.data);
            addToRecentScans(result.data);
        } else {
            showResult('error', result.message, result.data);
        }
        
        // Clear input
        document.getElementById('qr-data-input').value = '';
        
    } catch (error) {
        console.error('Error processing QR:', error);
        showResult('error', 'Format QR code tidak valid: ' + error.message);
    }
}

function showResult(type, message, data = null) {
    const resultDiv = document.getElementById('scan-result');
    const alertDiv = document.getElementById('result-alert');
    const contentDiv = document.getElementById('result-content');
    
    // Set alert class
    alertDiv.className = 'p-4 rounded-lg ' + (type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200');
    
    let content = `<div class="flex items-start">
        <i class="fas fa-${type === 'success' ? 'check-circle text-green-600' : 'exclamation-triangle text-red-600'} mt-1 mr-3"></i>
        <div class="flex-1">
            <h4 class="font-semibold ${type === 'success' ? 'text-green-800' : 'text-red-800'}">${message}</h4>`;
    
    if (data) {
        content += `
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="space-y-2">
                    <div><span class="font-medium ${type === 'success' ? 'text-green-700' : 'text-red-700'}">Nama:</span> ${data.nama_lengkap}</div>
                    <div><span class="font-medium ${type === 'success' ? 'text-green-700' : 'text-red-700'}">NIK:</span> ${data.nik}</div>
                    <div><span class="font-medium ${type === 'success' ? 'text-green-700' : 'text-red-700'}">Role:</span> ${data.role}</div>
                </div>
                <div class="space-y-2">
                    <div><span class="font-medium ${type === 'success' ? 'text-green-700' : 'text-red-700'}">Jumlah:</span> ${data.amount} kg</div>
                    <div><span class="font-medium ${type === 'success' ? 'text-green-700' : 'text-red-700'}">Status:</span> ${data.status}</div>
                    <div><span class="font-medium ${type === 'success' ? 'text-green-700' : 'text-red-700'}">RT/RW:</span> ${data.rt}/${data.rw}</div>
                </div>
            </div>
        `;
    }
    
    content += '</div></div>';
    contentDiv.innerHTML = content;
    resultDiv.classList.remove('hidden');
    
    // Auto hide after 10 seconds
    setTimeout(() => {
        resultDiv.classList.add('hidden');
    }, 10000);
}

function addToRecentScans(data) {
    const recentDiv = document.getElementById('recent-scans');
    const now = new Date().toLocaleTimeString('id-ID');
    
    // Remove "no scans" message if exists
    if (recentDiv.querySelector('.text-gray-500')) {
        recentDiv.innerHTML = '';
    }
    
    const scanItem = document.createElement('div');
    scanItem.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3';
    scanItem.innerHTML = `
        <div class="flex items-center">
            <div class="bg-green-100 p-2 rounded-full mr-3">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">${data.nama_lengkap}</h4>
                <p class="text-sm text-gray-600">${data.role} • ${data.amount} kg • ${data.status}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">${now}</p>
            <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Berhasil</span>
        </div>
    `;
    
    recentDiv.insertBefore(scanItem, recentDiv.firstChild);
    
    // Keep only last 5 scans
    while (recentDiv.children.length > 5) {
        recentDiv.removeChild(recentDiv.lastChild);
    }
}

// Check camera permission on page load
window.addEventListener('load', async () => {
    try {
        const permissions = await navigator.permissions.query({ name: 'camera' });
        if (permissions.state === 'denied') {
            showResult('error', 'Akses kamera ditolak. Silakan aktifkan kamera di pengaturan browser.');
        }
    } catch (err) {
        console.log('Permission API not supported');
    }
});
</script>

<style>
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<?php include 'views/layout/footer.php'; ?>