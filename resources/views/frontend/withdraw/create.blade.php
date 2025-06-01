@extends('layouts.layoutMain',['noBottomMenu' => true])
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
                <div class="nominal">
                    <p class="text-gray-600 mb-2">Masukkan Nominal</p>
                    <div class="mb-6">
                        <input type="text" name="amount" id="amount"
                            class="text-center shadow-xl border-0 border-green-500 text-xl rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-4
                            "placeholder="Rp0">
                    </div>
                </div>
                <div class="metode">
                    <p class="text-gray-600 mb-2">Metode Penarikan</p>
                    <select name="metode_penarikan" class="text-center shadow-xl border-0 border-green-500 text-xl rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-4" >
                        <option value="" selected disabled>Pilih metode</option>
                        <option value="transfer">Transfer</option>
                        <option value="cash">Cash</option>
                    </select>
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
  (function() {
    // Safety wrapper to avoid global namespace pollution
    function initWithdrawApp() {
        try {
            // Only start when the DOM is fully loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initOnReady);
            } else {
                initOnReady();
            }
        } catch (e) {
            console.error('Error initializing app:', e);
        }
    }

    function initOnReady() {
        try {
            // Wait for jQuery
            var checkJQueryInterval = setInterval(function() {
                if (window.jQuery) {
                    clearInterval(checkJQueryInterval);
                    setupEventHandlers();
                }
            }, 100);

            // Safety timeout
            setTimeout(function() {
                clearInterval(checkJQueryInterval);
                if (!window.jQuery) {
                    console.error('jQuery not found after timeout');
                }
            }, 5000);
        } catch (e) {
            console.error('Error in initOnReady:', e);
        }
    }

    // Setup event handlers after jQuery is ready
    function setupEventHandlers() {
        try {
            var $ = window.jQuery;

            // Form submission
            $('#createFormWithdraw').off('submit').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmission($(this));
            });

            // Format amount input
            $('#amount').on('input', function() {
                formatAmountInput($(this));
            });
        } catch (e) {
            console.error('Error in setupEventHandlers:', e);
        }
    }

    // Handle form submission
    function handleFormSubmission($form) {
        try {
            var $ = window.jQuery;
            var $submitBtn = $('#submitBtn');

            // Disable button
            $submitBtn.prop('disabled', true)
                      .text('Memproses...')
                      .addClass('opacity-70 cursor-not-allowed');

            // Unformat amount before submission
            const rawAmount = $('#amount').val();
            const unformattedAmount = hapusFormatAngka(rawAmount);
            $('#amount').val(unformattedAmount);

            // Submit form via AJAX
            $.ajax({
                url: '/tarik-tunai',
                type: 'POST',
                data: $form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.href = '/withdraw/waiting/' + response.withdrawId;
                    }, 2000);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const [field, messages] of Object.entries(errors)) {
                            messages.forEach(message => {
                                showToast('error', message);
                            });
                        }
                    } else {
                        showToast('error', xhr.responseJSON.error);
                    }
                    resetSubmitButton($submitBtn);
                }
            });
        } catch (e) {
            console.error('Error in handleFormSubmission:', e);
            resetSubmitButton($('#submitBtn'));
        }
    }

    // Format amount input with thousand separators
    function formatAmountInput($input) {
        try {
            let unformattedValue = hapusFormatAngka($input.val());
            if (unformattedValue !== '') {
                unformattedValue = parseInt(unformattedValue, 10);
                if (!isNaN(unformattedValue)) {
                    $input.val(formatAngka(unformattedValue));
                }
            } else {
                $input.val('');
            }
        } catch (e) {
            console.error('Error formatting amount input:', e);
        }
    }

    // Format number with thousand separators
    function formatAngka(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Remove thousand separators from number
    function hapusFormatAngka(value) {
        return value.replace(/\./g, '');
    }

    // Reset submit button to its initial state
    function resetSubmitButton($btn) {
        try {
            if ($btn && $btn.length) {
                $btn.prop('disabled', false)
                    .text('Tarik Tunai')
                    .removeClass('opacity-70 cursor-not-allowed');
            }
        } catch (e) {
            console.error('Error resetting button:', e);
        }
    }

    // Show toast message using SweetAlert2
    function showToast(icon, message) {
        try {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: icon,
                    title: message,
                    position: 'center',
                    showConfirmButton: false,
                    timer: 1500,
                });
            } else {
                alert(message); // Fallback to alert if SweetAlert2 is not available
            }
        } catch (e) {
            console.error('Error showing toast:', e);
            alert(message); // Fallback to alert if an error occurs
        }
    }

    // Start the app
    initWithdrawApp();
})();
</script>

@endsection
