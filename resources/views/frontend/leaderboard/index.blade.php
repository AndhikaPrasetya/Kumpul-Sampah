<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bank Sampah - Leaderboard</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('/template/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen max-w-screen-sm mx-auto bg-gray-100">
        <header class="bg-gray-50 py-4 px-4 border-b border-gray-200 relative text-center max-w-screen-sm mx-auto">
            <a href="#" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="text-base font-medium">Leaderboard</h1>
        </header>

        <!-- Main Content -->
        <main class="max-w-screen-sm pb-16">
            <!-- Header -->
            <div class="text-center my-4">
                <h1 class="text-xl font-bold text-gray-800">Pahlawan Lingkungan</h1>
            </div>
            
            
            <!-- Card Container -->
            <div class="bg-white rounded-b-lg shadow mx-4 overflow-hidden">
                <!-- Top 3 Winners -->
                <div class="flex justify-center p-6 relative bg-gray-50">

                    <!-- Second Place -->
                    <div class="flex flex-col items-center mx-2 z-10">
                
                        <div class="w-12 h-12 rounded-full  mt-1 border-2 border-gray-300 bg-gradient-to-br from-gray-300 to-gray-100 flex items-center justify-center shadow-lg relative">
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-gray-800 text-white text-xs font-bold flex items-center justify-center rounded-full">2</span>
                        <i class="fas fa-seedling text-green-600 text-sm"></i>
                        </div>
                
                        <p class="text-gray-800 font-medium mt-1 text-xs">Kelompok Hijau</p>
                        <p class="text-green-600 text-xs font-bold">875 kg</p>
                    </div>
                
                    <!-- First Place -->
                    <div class="flex flex-col items-center mx-2 z-20">
                
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mb-1">
                            <i class="fas fa-leaf text-white text-sm"></i>
                        </div>
                
                        <div class="w-16 h-16 rounded-full border-4 border-green-500 bg-gradient-to-br from-gray-300 to-gray-100 flex items-center justify-center shadow-xl relative">
                            <span class="absolute bottom-0 right-0 w-5 h-5 bg-green-700 text-white text-xs font-bold flex items-center justify-center rounded-full">1</span>
                            <i class="fas fa-seedling text-green-600 text-lg"></i>
                        </div>
                
                        <p class="text-gray-800 font-medium mt-1 text-sm">RT 05</p>
                        <p class="text-green-600 font-semibold text-sm">1.245 kg</p>
                    </div>
                
                    <!-- Third Place -->
                    <div class="flex flex-col items-center mx-2 z-10">
                
                        <div class="w-12 h-12 rounded-full border-2 border-gray-300 bg-gradient-to-br from-gray-300 to-gray-100 flex items-center justify-center shadow-lg relative">
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-amber-900 text-white text-xs font-bold flex items-center justify-center rounded-full">3</span>
                            <i class="fas fa-seedling text-green-600 text-sm"></i>
                        </div>
                
                        <p class="text-gray-800 font-medium mt-1 text-xs">Karang Taruna</p>
                        <p class="text-green-600 text-xs font-bold">720 kg</p>
                    </div>
                
                </div>
                
                <!-- Info Panel -->
                <div class="px-4 py-4 bg-gray-50 border-t border-b border-gray-200">
                    <div class="flex justify-between text-xs text-gray-700">
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600 mb-1">15.782</div>
                            <div>Total Kg</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600 mb-1">257</div>
                            <div>Partisipan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600 mb-1">Rp 12,6jt</div>
                            <div>Nilai</div>
                        </div>
                    </div>
                </div>
                
                <!-- List of other players -->
                <div class="px-4 py-2">
                    <!-- Player 4 -->
                    <div class="flex items-center bg-gray-50 p-3 rounded-lg mb-2 hover:bg-gray-100 transition">
                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center font-bold text-white mr-3">4</div>
                        <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center bg-gray-200">
                            <i class="fas fa-recycle text-green-600"></i>
                        </div>
                        <div class="ml-3 flex-grow">
                            <p class="text-gray-800 font-medium">Komplek Dahlia</p>
                            
                        </div>
                        <div class="text-right">
                            <p class="text-green-600 font-semibold">682 kg</p>
                        </div>
                    </div>
                   
                </div>
                
                <!-- Footer -->
                <div class="p-4 border-t border-gray-200 flex justify-between items-center">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg font-medium text-sm transition flex items-center">
                        <i class="fas fa-calendar-week mr-2"></i> Jadwal
                    </button>
                    <button class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg font-medium text-sm transition flex items-center">
                        <i class="fas fa-share-alt mr-2"></i> Bagikan
                    </button>
                </div>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <div class="max-w-screen-sm fixed bottom-0 left-0 right-0 w-full mx-auto bg-white border-t border-gray-300 flex items-center justify-around py-2">
            <a href="#" class="flex flex-col items-center text-gray-600">
                <ion-icon name="home-outline" class="text-2xl"></ion-icon>
                <span class="text-xs">Home</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-600">
                <ion-icon name="newspaper-outline" class="text-2xl"></ion-icon>
                <span class="text-xs">Info</span>
            </a>
            <a href="#" class="flex flex-col items-center text-green-500 font-bold">
                <ion-icon name="podium" class="text-2xl"></ion-icon>
                <span class="text-xs">Peringkat</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-600">
                <ion-icon name="person-circle-outline" class="text-2xl"></ion-icon>
                <span class="text-xs">Profile</span>
            </a>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>