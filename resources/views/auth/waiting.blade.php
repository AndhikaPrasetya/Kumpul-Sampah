@extends('layouts.layoutMain',['noBottomMenu' => true,'noHeader' => true])
@section('headTitle', 'BSU')
@section('content')
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-500 p-8 text-center text-white">
            <div class="pulse-animation">
                <i class="fas fa-hourglass-half text-5xl mb-4"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Terima Kasih!</h1>
            <p class="text-white-100">Pendaftaran BSU Anda telah berhasil diterima.</p>
        </div>
        
        <!-- Content Section -->
        <div class="p-6 space-y-6">
            
            <!-- Status Card -->
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
                <h3 class="font-semibold text-yellow-700 mb-2">
                    <i class="fas fa-clock mr-2 bounce-animation"></i>
                    Status: Menunggu Approval
                </h3>
                <p class="text-yellow-800 text-sm">Akun BSU Anda sedang dalam proses peninjauan oleh tim admin. Mohon tunggu konfirmasi lebih lanjut.</p>
            </div>
            
            <!-- Progress Steps -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- Step 1: Completed -->
                <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 text-center">
                    <i class="fas fa-user-check text-2xl text-green-600 mb-2"></i>
                    <p class="text-sm text-green-700 font-medium">Pendaftaran Berhasil</p>
                </div>
                
                <!-- Step 2: Active -->
                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4 text-center">
                    <i class="fas fa-search text-2xl text-yellow-600 mb-2"></i>
                    <p class="text-sm text-yellow-700 font-medium">Sedang Ditinjau</p>
                </div>
                
                <!-- Step 3: Pending -->
                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 text-center">
                    <i class="fas fa-envelope text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-600 font-medium">Konfirmasi Email</p>
                </div>
            </div>
            
            <!-- Information Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-700 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    Langkah Selanjutnya:
                </h4>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-center">
                        <i class="fas fa-envelope-open-text text-blue-600 mr-2"></i>
                        Cek email Anda secara berkala untuk konfirmasi
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                        Proses peninjauan membutuhkan waktu 1-3 hari kerja
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                        Pastikan dokumen yang dikirim sudah lengkap dan valid
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone text-blue-600 mr-2"></i>
                        Hubungi admin jika ada pertanyaan lebih lanjut
                    </li>
                </ul>
            </div>
              <div class="space-y-3">
                <a 
                    href="{{route('login')}}" 
                    class="block w-full text-center bg-transparent border-2 border-gray-400 text-gray-600 font-medium py-3 px-6 rounded-lg transition duration-300 hover:bg-gray-400 hover:text-white"
                >
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Halaman Login 
                </a>
            </div>
            
        </div>
        
        <!-- Footer Section -->
        <div class="bg-gray-50 p-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                <i class="fas fa-headset mr-1"></i>
                Jika ada pertanyaan, silakan hubungi layanan pelanggan kami di email
                <a href="mailto:admin@bsu.com" class="text-green-600 hover:text-green-700 font-medium">admin@bsu.com</a>
            </p>
        </div>
    </div>
    
    <!-- Toast Notification (Hidden by default) -->
    <div id="toast" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg hidden transition-all duration-300">
        <i class="fas fa-info-circle mr-2"></i>
        <span>Silakan cek email Anda untuk konfirmasi lebih lanjut</span>
    </div>
@endsection
@section('script')
      <script>
      
        
        function showToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('hidden');
            
            // Hilangkan toast setelah 3 detik
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
        
        // Simulasi update status secara real-time (optional)
        function simulateStatusUpdate() {
            const statusCard = document.querySelector('.bg-yellow-50');
            const steps = document.querySelectorAll('.grid > div');
            
            // Contoh update status setelah 10 detik (hanya untuk demo)
            setTimeout(() => {
                statusCard.innerHTML = `
                    <h3 class="font-semibold text-green-700 mb-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        Status: Sedang Diproses
                    </h3>
                    <p class="text-green-800 text-sm">Admin sedang memverifikasi dokumen Anda. Konfirmasi akan dikirim melalui email.</p>
                `;
                statusCard.className = 'bg-green-50 border-2 border-green-200 rounded-lg p-4';
                
                // Update step 2 to completed
                steps[1].innerHTML = `
                    <i class="fas fa-check-circle text-2xl text-green-600 mb-2"></i>
                    <p class="text-sm text-green-700 font-medium">Ditinjau</p>
                `;
                steps[1].className = 'bg-green-50 border-2 border-green-200 rounded-lg p-4 text-center';
                
                // Update step 3 to active
                steps[2].innerHTML = `
                    <i class="fas fa-envelope text-2xl text-yellow-600 mb-2"></i>
                    <p class="text-sm text-yellow-700 font-medium">Konfirmasi Email</p>
                `;
                steps[2].className = 'bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4 text-center';
            }, 10000);
        }
        
        // Jalankan simulasi (uncomment jika ingin demo)
        // simulateStatusUpdate();
    </script>
@endsection