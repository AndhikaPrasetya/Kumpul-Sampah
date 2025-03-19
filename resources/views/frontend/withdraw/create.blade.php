@extends('layouts.layoutSecond')
@section('headTitle', 'Tarik Tunai')
@section('title', 'Tarik Tunai')
@section('content')
    <div class="max-w-md mx-auto p-4 h-screen flex flex-col">
        <!-- User Info and Transfer Type Header -->
        <div class="bg-white from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-center">
            <div class="text-black">
                <p class="text-sm opacity-80 mb-1">Total Saldo</p>
                <h2 class="text-xl lg:text-3xl font-bold mb-4">Rp {{ number_format($saldoNasabah->balance, 0, ',', '.') }}</h2>

                <div class="flex justify-center mt-4 pt-4 border-t border-black border-opacity-20">
                    <div>
                        <p class="text-xs opacity-80">Tertahan</p>
                        <p class="text-lg font-medium">Rp {{ number_format($saldoTertahan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Amount Display Section -->
        <form id="createFormWithdraw" method="POST">
            @csrf
            <div class="text-center mb-10">
                <p class="text-gray-600 mb-2">Masukkan Nominal</p>
                <div class="mb-6">
                    <input type="text" name="amount" id="amount"
                        class="text-center shadow-xl border-0 border-green-500 text-xl rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-4
                        placeholder="Rp
                        0">

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-10">
                <button type="submit" id="submitBtn"
                    class="w-full bg-green-700 hover:bg-green-800 text-white py-4 rounded-xl font-medium transition duration-200">
                    Tarik Tunai
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
                "positionClass": "toast-bottom-right",
                "timeOut": "1000",
            };

            const showToast = (icon, message) => {
                if (icon === 'error') {
                    toastr.error(message);
                } else if (icon === 'success') {
                    toastr.success(message);
                } else if (icon === 'info') {
                    toastr.info(message);
                } else {
                    toastr.warning(message);
                }
            };
            $('#createFormWithdraw').on('submit', function(e) {
                e.preventDefault();
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.text('Memproses...');
                submitBtn.addClass('opacity-70 cursor-not-allowed');
                // Ambil nilai amount dan hapus format titik
                const rawAmount = $('#amount').val();
                const unformattedAmount = hapusFormatAngka(rawAmount);

                // Update nilai amount di form sebelum dikirim
                $('#amount').val(unformattedAmount);

                $.ajax({
                    url: '/tarik-tunai',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    },
                    error: (xhr) => {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (const [field, messages] of Object.entries(errors)) {
                                messages.forEach(message => {
                                    showToast('error', message);

                                });

                            }

                        } else {
                            showToast('error', xhr.responseJSON.error);
                            submitBtn.prop('disabled', false);
                            submitBtn.text('Tarik Tunai');
                            submitBtn.removeClass('opacity-70 cursor-not-allowed');
                        }
                    }
                });
            });

            // Format angka dengan titik sebagai pemisah ribuan
            function formatAngka(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Hapus format angka sebelum dikirim ke server
            function hapusFormatAngka(value) {
                return value.replace(/\./g, '');
            }

            // Event untuk input balance (format otomatis)
            $('#amount').on('input', function() {
                let unformattedValue = hapusFormatAngka($(this).val());

                // Pastikan hanya angka yang diproses
                if (unformattedValue !== '') {
                    unformattedValue = parseInt(unformattedValue, 10);
                    if (!isNaN(unformattedValue)) {
                        $(this).val(formatAngka(unformattedValue));
                    }
                } else {
                    $(this).val('');
                }
            });

        });
    </script>
@endsection
