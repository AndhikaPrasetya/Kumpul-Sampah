@extends('layouts.layoutSecond')
@section('headTitle', 'Rewards')
@section('title', 'Rewards')
@section('content')
<div class="relative w-full h-56 sm:h-64 md:h-72 rounded-lg overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-green-900 opacity-70"></div>
    <img src="{{ asset($rewards->image) }}"
         alt="Voucher Starbucks" 
         class="w-full h-full object-cover object-center rounded-lg">
    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
      <h2 class="text-xl sm:text-2xl font-bold">{{$rewards->name}}</h2>
      <div class="flex items-center mt-2">
        <span class="bg-green-500 text-white text-sm px-3 py-1 rounded-full font-medium">
          {{number_format($rewards->points,0,'.',',')}} Poin
        </span>
      </div>
    </div>
</div>

<div class="container mx-auto px-4 py-5">
    <div class="bg-white rounded-xl shadow-md p-4 mb-5 -mt-6 relative z-10">
        <div class="flex items-center justify-between">
            <div class="text-center flex-1">
                <p class="text-xs md:text-sm text-gray-500">Poin Anda</p>
                <p class="text-lg font-bold text-gray-800">{{number_format($currentPoints,0,'.',',')}}</p>
            </div>
            <div class="h-10 border-l border-gray-200"></div>
            <div class="text-center flex-1">
                <p class="text-xs md:text-sm text-gray-500">Dibutuhkan</p>
                <p class="text-lg font-bold text-gray-800"> @if($needPoints <= 0)
                    <span class="text-green-500">Poin cukup</span>
                @else
                    {{ number_format($needPoints, 0, '.', ',') }}
                @endif</p>
            </div>
        </div>
    </div>
    
 <!-- Description -->
 <div class="bg-white rounded-xl shadow-md p-5 mb-5">
    <h3 class="text-lg font-medium text-gray-800 mb-2">Deskripsi</h3>
    <p class="text-gray-600 text-sm">
      Nikmati segelas kopi atau minuman favoritmu di Starbucks dengan voucher senilai Rp50.000. Voucher ini dapat digunakan di seluruh gerai Starbucks di Indonesia.
    </p>
  </div>
  
  <!-- Terms and conditions -->
  <div class="bg-white rounded-xl shadow-md p-5 mb-5">
    <h3 class="text-lg font-medium text-gray-800 mb-2">Syarat & Ketentuan</h3>
    <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
      <li>Berlaku hingga 30 Juni 2023</li>
      <li>Tidak dapat ditukar dengan uang tunai</li>
      <li>Hanya berlaku untuk 1 kali penukaran</li>
      <li>Voucher harus digunakan dalam sekali transaksi</li>
      <li>Jika nilai transaksi kurang dari Rp50.000, sisa nilai tidak dapat dikembalikan</li>
    </ul>
  </div>
  
  <!-- How to use -->
  <div class="bg-white rounded-xl shadow-md p-5 mb-20">
    <h3 class="text-lg font-medium text-gray-800 mb-2">Cara Menggunakan</h3>
    <ol class="list-decimal list-inside text-gray-600 text-sm space-y-3">
      <li>Tukarkan poin Anda dengan reward ini</li>
      <li>Kode voucher digital akan dikirimkan ke email dan notifikasi aplikasi Anda</li>
      <li>Tunjukkan kode voucher kepada kasir saat melakukan pemesanan</li>
      <li>Kode voucher akan divalidasi oleh kasir</li>
      <li>Nikmati minuman favoritmu!</li>
    </ol>
  </div>
</div>
    
    <form id="redeemRewardForm">
        @csrf
        <input type="hidden" name="reward_id" value="{{$rewards->id}}">
        <input type="hidden" name="points" value="{{$rewards->points}}">
        <div class="max-w-screen-sm mx-auto fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
            <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Tukar Reward
            </button>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
$(document).ready(() => {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "1000",
    };

    const showToast = (icon, message) => {
        if (icon === 'error') {
            toastr.error(message);
        } else if (icon === 'success') {
            toastr.success(message);
        } else {
            toastr.info(message);
        }
    };

    $('#redeemRewardForm').on('submit', function(e) {
        e.preventDefault();
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).text('Memproses...').addClass('opacity-70 cursor-not-allowed');

        $.ajax({
            url: '/rewards',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showToast('success', response.message);
                setTimeout(() => window.location.reload(), 2000);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    Object.values(xhr.responseJSON.errors).forEach(messages => {
                        messages.forEach(message => showToast('error', message));
                    });
                } else {
                    showToast('error', xhr.responseJSON.error || 'Terjadi kesalahan.');
                }
                submitBtn.prop('disabled', false).text('Tukar Reward').removeClass('opacity-70 cursor-not-allowed');
            }
        });
    });
});
</script>
@endsection
