@extends('layouts.layoutMain',['noBottomMenu' => true])
@section('title', 'Peringkat')
@section('content')


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
     
        @endsection

    