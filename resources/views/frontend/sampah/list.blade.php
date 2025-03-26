{{-- @extends('layouts.layoutMain')
@section('title', 'Harga Sampah Terkini')
@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Header Tabel -->
    <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-100 font-semibold text-sm text-gray-600">
        <div>Jenis Sampah</div>
        <div class="text-right">Harga/kg</div>
        <div class="text-right">Points/kg</div>
    </div>

    <!-- Baris Data Sampah -->
    <div class="divide-y divide-gray-100">
        <!-- Item 1 -->
        @foreach ($sampahs as $sampah)
            
        <div class="grid grid-cols-3 gap-4 p-4 items-center hover:bg-gray-50 transition-colors">
            <div class="flex items-center">
                <span class="font-medium">{{$sampah->nama}}</span>
            </div>
            <div class="text-right text-green-600 font-medium">Rp {{ number_format($sampah->harga, 0, ',', '.') }}</div>
            <div class="text-right text-gray-600">{{ number_format($sampah->points, 0, ',', '.') }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection --}}

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoSampah - Aplikasi Kumpulkan Sampah</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
</head>
<body class="bg-gray-100 font-sans">
  <div class="max-w-md mx-auto bg-white min-h-screen flex flex-col">
    <!-- Header with gradient background -->
    <nav class="bg-gradient-to-r from-emerald-500 to-green-600 text-white p-6 rounded-b-3xl shadow-lg">
      <div class="flex justify-between items-center mb-3">
        <h1 class="text-xl font-bold">EcoSampah</h1>
        <div class="flex gap-2">
          <button class="p-2 bg-white/20 rounded-full hover:bg-white/30 transition">
            <i class="fas fa-bell text-sm"></i>
          </button>
          <button class="p-2 bg-white/20 rounded-full hover:bg-white/30 transition">
            <i class="fas fa-share-alt text-sm"></i>
          </button>
        </div>
      </div>
    </nav>
      
      <div class="mt-6 mb-2">
        <p class="text-lg font-medium">Selamat Siang, Nasabah!</p>
        <h2 class="text-2xl font-bold flex items-center gap-2">
          <i class="fas fa-recycle"></i>
          Ayo Kumpulkan Sampah dan Setorkan!
        </h2>
      </div>

    <!-- Balance Card -->
    <div class="mx-4 -mt-6 bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
      <div class="p-6 text-center">
        <p class="text-gray-600 font-medium">Saldo</p>
        <h3 class="text-4xl font-bold mt-1 mb-2 text-gray-800">Rp 0</h3>
        <div class="inline-block bg-amber-400 text-white px-3 py-1 rounded-full text-sm font-medium">
          <i class="fas fa-coins mr-1"></i>
          0 Point
        </div>
      </div>
      
      <!-- Action Buttons -->
      <div class="grid grid-cols-3 divide-x divide-gray-100 border-t border-gray-100">
        <button class="flex flex-col items-center py-4 hover:bg-gray-50 transition">
          <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center text-white mb-2">
            <i class="fas fa-upload"></i>
          </div>
          <span class="text-sm font-medium text-gray-700">Tarik Tunai</span>
        </button>
        
        <button class="flex flex-col items-center py-4 hover:bg-gray-50 transition">
          <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center text-white mb-2">
            <i class="fas fa-sync-alt"></i>
          </div>
          <span class="text-sm font-medium text-gray-700">Tukar Point</span>
        </button>
        
        <button class="flex flex-col items-center py-4 hover:bg-gray-50 transition">
          <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center text-white mb-2">
            <i class="fas fa-history"></i>
          </div>
          <span class="text-sm font-medium text-gray-700">Riwayat</span>
        </button>
      </div>
    </div>

    <!-- Setor Sampah Card -->
    <div class="mx-4 mt-4 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="bg-amber-50 p-3 rounded-xl">
            <img src="/api/placeholder/70/70" alt="Sampah Box" class="w-12 h-12 object-contain"/>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Setor Sampah</h3>
            <p class="text-gray-500 text-sm">Timbang sampah Anda untuk mengetahui jumlah yang dapat ditukar.</p>
          </div>
        </div>
        <button class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center hover:bg-emerald-600 transition">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>

    <!-- Transaksi Section -->
    <div class="mx-4 mt-6">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-xl font-bold text-gray-800">Transaksi</h2>
        <a href="#" class="text-emerald-500 font-medium text-sm">Lihat semua</a>
      </div>
      
      <div class="bg-white rounded-2xl p-6 flex items-center justify-center shadow-sm border border-gray-100">
        <div class="text-center text-gray-500">
          <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
          <p>Belum ada transaksi</p>
        </div>
      </div>
    </div>
    <div class="mx-4 mt-6">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-xl font-bold text-gray-800">Transaksi</h2>
          <a href="#" class="text-emerald-500 font-medium text-sm">Lihat semua</a>
        </div>
        
        <div class="transactions-slider-container relative">
          <div class="transactions-slider">
            <!-- Transaction Item 1 -->
            <div class="px-2">
              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                      <i class="fas fa-recycle"></i>
                    </div>
                    <div>
                      <h3 class="font-medium text-gray-800">Setor Sampah Plastik</h3>
                      <p class="text-xs text-gray-500">20 Mar 2025 · 14:30</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-emerald-600">+Rp 15.000</p>
                    <p class="text-xs text-gray-500">2.5 kg</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Transaction Item 2 -->
            <div class="px-2">
              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                      <i class="fas fa-recycle"></i>
                    </div>
                    <div>
                      <h3 class="font-medium text-gray-800">Setor Sampah Kertas</h3>
                      <p class="text-xs text-gray-500">15 Mar 2025 · 10:15</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-emerald-600">+Rp 8.500</p>
                    <p class="text-xs text-gray-500">1.7 kg</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Transaction Item 3 -->
            <div class="px-2">
              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                      <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div>
                      <h3 class="font-medium text-gray-800">Tarik Tunai</h3>
                      <p class="text-xs text-gray-500">10 Mar 2025 · 16:45</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-red-500">-Rp 20.000</p>
                    <p class="text-xs text-gray-500">Bank Transfer</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Transaction Item 4 -->
            <div class="px-2">
              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                      <i class="fas fa-gift"></i>
                    </div>
                    <div>
                      <h3 class="font-medium text-gray-800">Redeem Voucher</h3>
                      <p class="text-xs text-gray-500">5 Mar 2025 · 09:20</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-red-500">-500 points</p>
                    <p class="text-xs text-gray-500">Pulsa Rp 25.000</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Transaction Item 5 -->
            <div class="px-2">
              <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                      <i class="fas fa-coins"></i>
                    </div>
                    <div>
                      <h3 class="font-medium text-gray-800">Bonus Points</h3>
                      <p class="text-xs text-gray-500">1 Mar 2025 · 12:00</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-emerald-600">+100 points</p>
                    <p class="text-xs text-gray-500">Referral Bonus</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Transactions Slider Controls -->
          <button class="absolute -left-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center z-10 border border-gray-100 text-gray-400 hover:text-emerald-500">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="absolute -right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center z-10 border border-gray-100 text-gray-400 hover:text-emerald-500">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
        
        <!-- Transaction Summary Card -->
        <div class="mt-4 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4">
          <h3 class="font-bold text-gray-800 mb-3">Ringkasan Transaksi</h3>
          <div class="grid grid-cols-3 gap-3">
            <div class="bg-green-50 p-3 rounded-xl text-center">
              <p class="text-xs text-gray-500">Total Setor</p>
              <p class="font-bold text-gray-800 mt-1">4.2 kg</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-xl text-center">
              <p class="text-xs text-gray-500">Total Points</p>
              <p class="font-bold text-gray-800 mt-1">600 pts</p>
            </div>
            <div class="bg-amber-50 p-3 rounded-xl text-center">
              <p class="text-xs text-gray-500">Pendapatan</p>
              <p class="font-bold text-gray-800 mt-1">Rp 23.500</p>
            </div>
          </div>
        </div>
      </div>
      

    <!-- Berita Section -->
    <div class="mx-4 mt-6">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-xl font-bold text-gray-800">Berita terbaru</h2>
        <a href="#" class="text-emerald-500 font-medium text-sm">Lihat semua</a>
      </div>
      
      <div class="news-slider-container relative">
        <div class="news-slider">
          <!-- News Item 1 -->
          <div class="px-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <img src="/api/placeholder/320/160" alt="Berita 1" class="w-full h-40 object-cover"/>
              <div class="p-4">
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Lingkungan</span>
                <h3 class="font-bold text-gray-800 mt-2">Program Daur Ulang Kota Baru Mencapai Target 2025</h3>
                <p class="text-gray-500 text-sm mt-1 line-clamp-2">Program daur ulang yang diinisiasi pemerintah kota telah berhasil mengurangi sampah plastik hingga 45% dalam 6 bulan terakhir...</p>
                <div class="flex justify-between items-center mt-3">
                  <span class="text-xs text-gray-400">23 Mar 2025</span>
                  <button class="text-emerald-500 text-sm font-medium">Baca Selengkapnya</button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- News Item 2 -->
          <div class="px-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <img src="/api/placeholder/320/160" alt="Berita 2" class="w-full h-40 object-cover"/>
              <div class="p-4">
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Teknologi</span>
                <h3 class="font-bold text-gray-800 mt-2">Aplikasi EcoSampah Menang Penghargaan Inovasi Hijau</h3>
                <p class="text-gray-500 text-sm mt-1 line-clamp-2">Aplikasi pengelolaan sampah EcoSampah berhasil meraih penghargaan prestisius di ajang Teknologi Hijau Indonesia 2025...</p>
                <div class="flex justify-between items-center mt-3">
                  <span class="text-xs text-gray-400">20 Mar 2025</span>
                  <button class="text-emerald-500 text-sm font-medium">Baca Selengkapnya</button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- News Item 3 -->
          <div class="px-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <img src="/api/placeholder/320/160" alt="Berita 3" class="w-full h-40 object-cover"/>
              <div class="p-4">
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Edukasi</span>
                <h3 class="font-bold text-gray-800 mt-2">Workshop Pengelolaan Sampah Rumah Tangga Bulan Depan</h3>
                <p class="text-gray-500 text-sm mt-1 line-clamp-2">EcoSampah akan mengadakan workshop gratis tentang cara efektif mengelola sampah rumah tangga pada tanggal 15 April...</p>
                <div class="flex justify-between items-center mt-3">
                  <span class="text-xs text-gray-400">18 Mar 2025</span>
                  <button class="text-emerald-500 text-sm font-medium">Baca Selengkapnya</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- News Slider Controls -->
        <button class="absolute -left-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center z-10 border border-gray-100 text-gray-400 hover:text-emerald-500">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="absolute -right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center z-10 border border-gray-100 text-gray-400 hover:text-emerald-500">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>

    <!-- Rewards Section -->
    <div class="mx-4 mt-6 mb-20">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-xl font-bold text-gray-800">Rewards</h2>
        <a href="#" class="text-emerald-500 font-medium text-sm">Lihat semua</a>
      </div>
      
      <div class="rewards-slider-container relative">
        <div class="rewards-slider">
          <!-- Reward Item 1 -->
          <div class="px-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <div class="relative">
                <img src="/api/placeholder/160/120" alt="Reward 1" class="w-full h-32 object-cover"/>
                <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">HOT</div>
              </div>
              <div class="p-4">
                <h3 class="font-bold text-gray-800">Voucher Belanja Rp 50.000</h3>
                <div class="flex items-center mt-2">
                  <i class="fas fa-coins text-amber-400 mr-1"></i>
                  <span class="font-bold text-gray-800">1000 points</span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                  <div class="w-2/3 bg-gray-200 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: 75%"></div>
                  </div>
                  <span class="text-xs text-gray-500">75% claimed</span>
                </div>
                <button class="w-full mt-4 bg-emerald-500 text-white py-2 rounded-lg font-medium hover:bg-emerald-600 transition">Tukarkan</button>
              </div>
            </div>
          </div>
          
        
          
          <!-- Reward Item 3 -->
          <div class="px-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <div class="relative">
                <img src="/api/placeholder/160/120" alt="Reward 3" class="w-full h-32 object-cover"/>
                <div class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-lg">POPULAR</div>
              </div>
              <div class="p-4">
                <h3 class="font-bold text-gray-800">Paket Eco-Friendly Kit</h3>
                <div class="flex items-center mt-2">
                  <i class="fas fa-coins text-amber-400 mr-1"></i>
                  <span class="font-bold text-gray-800">1500 points</span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                  <div class="w-2/3 bg-gray-200 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: 90%"></div>
                  </div>
                  <span class="text-xs text-gray-500">90% claimed</span>
                </div>
                <button class="w-full mt-4 bg-emerald-500 text-white py-2 rounded-lg font-medium hover:bg-emerald-600 transition">Tukarkan</button>
              </div>
            </div>
          </div>
          
          <!-- Reward Item 4 -->
          <div class="px-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <div class="relative">
                <img src="/api/placeholder/160/120" alt="Reward 4" class="w-full h-32 object-cover"/>
                <div class="absolute top-2 right-2 bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-lg">LIMITED</div>
              </div>
              <div class="p-4">
                <h3 class="font-bold text-gray-800">Voucher Pulsa Rp 25.000</h3>
                <div class="flex items-center mt-2">
                  <i class="fas fa-coins text-amber-400 mr-1"></i>
                  <span class="font-bold text-gray-800">500 points</span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                  <div class="w-2/3 bg-gray-200 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: 60%"></div>
                  </div>
                  <span class="text-xs text-gray-500">60% claimed</span>
                </div>
                <button class="w-full mt-4 bg-emerald-500 text-white py-2 rounded-lg font-medium hover:bg-emerald-600 transition">Tukarkan</button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Rewards Slider Controls -->
        <button class="absolute -left-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center z-10 border border-gray-100 text-gray-400 hover:text-emerald-500">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="absolute -right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center z-10 border border-gray-100 text-gray-400 hover:text-emerald-500">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 py-2 px-4 max-w-md mx-auto">
        <div class="flex justify-between items-center">
          <button class="flex flex-col items-center w-1/4 py-2 text-emerald-600">
            <i class="fas fa-home text-xl"></i>
            <span class="text-xs mt-1 font-medium">Home</span>
          </button>
          
          <button class="flex flex-col items-center w-1/4 py-2 text-gray-500 hover:text-emerald-600">
            <i class="fas fa-file-alt text-xl"></i>
            <span class="text-xs mt-1">Artikel</span>
          </button>
          
          <button class="flex flex-col items-center w-1/4 py-2 text-gray-500 hover:text-emerald-600">
            <i class="fas fa-chart-line text-xl"></i>
            <span class="text-xs mt-1">Peringkat</span>
          </button>
          
          <button class="flex flex-col items-center w-1/4 py-2 text-gray-500 hover:text-emerald-600">
            <i class="fas fa-user text-xl"></i>
            <span class="text-xs mt-1">Profile</span>
          </button>
        </div>
      </nav>
         <!-- Slider Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Initialize News Slider
          let newsSlider = tns({
            container: '.news-slider',
            items: 1.2,
            gutter: 12,
            slideBy: 1,
            autoplay: false,
            controls: true,
            nav: false,
            controlsContainer: '.news-slider-container',
            prevButton: '.news-slider-container button:first-child',
            nextButton: '.news-slider-container button:last-child',
            mouseDrag: true,
            responsive: {
              640: {
                items: 1.5
              }
            }
          });
          
          // Initialize Rewards Slider
          let rewardsSlider = tns({
            container: '.rewards-slider',
            items: 1.2,
            gutter: 12,
            slideBy: 1,
            autoplay: false,
            controls: true,
            nav: false,
            controlsContainer: '.rewards-slider-container',
            prevButton: '.rewards-slider-container button:first-child',
            nextButton: '.rewards-slider-container button:last-child',
            mouseDrag: true,
            responsive: {
              640: {
                items: 2.5
              }
            }
          });
        });
      </script>
</body>


</html>
 