<div class="bg-white rounded-2xl p-6 shadow-md flex items-center justify-between">
    <div>
        <span class="text-sm text-gray-500 font-medium block mb-1">Saldo</span>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rp {{ number_format($saldo, 0, ',', '.') }}</h1>
        <a href="#" class="inline-block bg-yellow-500 text-white text-xs font-semibold px-3 py-1 rounded-full hover:bg-yellow-600 transition">
            {{ $points }} Poin <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
            </svg>
        </a>
    </div>
    <div class="bg-green-100 rounded-full p-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
</div>