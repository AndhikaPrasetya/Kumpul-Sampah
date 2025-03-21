@extends('layouts.layoutSecond')
@section('title', 'Setor Sampah')
@section('content')
    @php
        $icons = [
            'Plastik' => 'fa-recycle',
            'Bodong A' => 'fa-trash-alt',
            'Tutup Botol' => 'fa-wine-bottle',
            'Tutup Galon' => 'fa-prescription-bottle',
            'Ember Warna' => 'fa-box',
            'Ember Hitam' => 'fa-box',
            'Paralon' => 'fa-water',
            'Naso' => 'fa-question-circle',
            'Kresek' => 'fa-shopping-bag',
            'Galon Aqua' => 'fa-tint',
            'Akrilik' => 'fa-layer-group',
            'Gelas Kotor' => 'fa-glass-whiskey',
            'Inject' => 'fa-syringe',
            'Mainan' => 'fa-puzzle-piece',
        ];
    @endphp
    <div class="mb-20">
        <form id="createFormTransaction">
            @csrf
            <input type="hidden" name="total_amount" id="total_amount_hidden" value="0">
            <input type="hidden" name="total_points" id="total_points_hidden" value="0">

            @foreach ($kategoriSampah as $kategori)
            @if (isset($groupedSampahs[$kategori->id]) && count($groupedSampahs[$kategori->id]) > 0)
                <div class="category-card bg-white rounded-xl shadow-sm mb-4 overflow-hidden">
                    <div class="flex justify-between items-center p-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3 text-red-500">
                                <i class="fas {{ $icons[$kategori->nama] ?? 'fa-trash' }} text-sm"></i>
                            </div>
                            <span class="font-medium">{{ $kategori->nama }}</span>
                        </div>
                        <i class="fas fa-chevron-up text-gray-400 toggle-icon" data-category-id="{{ $kategori->id }}"></i>
                    </div>
        
                    <div class="category-content" id="category-content-{{ $kategori->id }}">
                        @foreach ($groupedSampahs[$kategori->id] as $sampah)
                            <div class="flex justify-between items-center p-3 border-b border-gray-100">
                                <div>
                                    <div class="font-medium">{{ $sampah->nama }}</div>
                                    <div class="text-xs text-gray-400">Harga: Rp {{ number_format($sampah->harga, 0, ',', '.') }}/kg</div>
                                    <div class="text-xs text-gray-400">Points: {{ $sampah->points }}/kg</div>
                                </div>
                                <div class="flex items-center sampah-item" data-id="{{ $sampah->id }}">
                                    <button type="button" class="w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center btn-minus">
                                        <i class="fas fa-minus text-xs text-gray-400"></i>
                                    </button>
                                    <span class="mx-3 text-sm min-w-8 text-center berat-value" data-harga="{{ $sampah->harga }}" data-points="{{ $sampah->points }}">0</span>
                                    <button type="button" class="w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center btn-plus">
                                        <i class="fas fa-plus text-xs text-gray-400"></i>
                                    </button>
                                    <input type="hidden" name="sampah_id[]" value="{{ $sampah->id }}">
                                    <input type="hidden" name="berat[]" class="berat-hidden" value="0">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @endforeach
            <div
                class="max-w-screen-sm mx-auto fixed bottom-5 left-5 right-5 bg-green-600 text-white p-3 rounded-lg shadow-lg flex justify-between items-center">
                <div class="text-sm">
                    <span class="font-medium">Total Sampah</span> | <span class="font-bold total-berat">0 Kg</span>
                </div>
                <button id="submitBtn" class="font-bold text-white" type="submit">
                    Setor
                </button>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
// Make sure to isolate our code from other scripts
(function() {
    // Safety wrapper to avoid global namespace pollution and conflicts
    function initSetorSampahApp() {
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
            $('#createFormTransaction').off('submit').on('submit', function(e) {
                e.preventDefault();
                handleFormSubmission();
            });

            // Category toggle
            $('.toggle-icon').off('click').on('click', function() {
                var categoryId = $(this).data('category-id');
                var content = $('#category-content-' + categoryId);
                if (content.length) {
                    content.slideToggle('fast');
                    $(this).toggleClass('fa-chevron-up fa-chevron-down');
                }
            });

            // Plus button
            $('.btn-plus').off('click').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                var $value = $this.siblings('.berat-value');
                var $hidden = $this.siblings('.berat-hidden');
                
                if ($value.length && $hidden.length) {
                    var val = parseFloat($value.text()) || 0;
                    val += 1;
                    $value.text(val);
                    $hidden.val(val);
                    calculateTotals();
                }
            });

            // Minus button
            $('.btn-minus').off('click').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                var $value = $this.siblings('.berat-value');
                var $hidden = $this.siblings('.berat-hidden');
                
                if ($value.length && $hidden.length) {
                    var val = parseFloat($value.text()) || 0;
                    val = Math.max(0, val - 1);
                    $value.text(val);
                    $hidden.val(val);
                    calculateTotals();
                }
            });

            // Calculate initial totals
            calculateTotals();
        } catch (e) {
            console.error('Error in setupEventHandlers:', e);
        }
    }

    // Calculate totals
    function calculateTotals() {
        try {
            var $ = window.jQuery;
            var totalAmount = 0;
            var totalPoints = 0;
            var totalBerat = 0;

            $('.berat-value').each(function() {
                try {
                    var $this = $(this);
                    var beratText = $this.text().trim();
                    var berat = parseFloat(beratText) || 0;
                    var harga = parseFloat($this.attr('data-harga')) || 0;
                    var points = parseFloat($this.attr('data-points')) || 0;

                    if (berat > 0) {
                        totalAmount += harga * berat;
                        totalPoints += points * berat;
                        totalBerat += berat;
                    }
                } catch (e) {
                    console.error('Error processing item:', e);
                }
            });

            $('#total_amount_hidden').val(totalAmount);
            $('#total_points_hidden').val(totalPoints);
            $('.total-berat').text(totalBerat.toFixed(1) + ' KG');
        } catch (e) {
            console.error('Error calculating totals:', e);
        }
    }

    // Form submission handler
    function handleFormSubmission() {
        try {
            var $ = window.jQuery;
            var $form = $('#createFormTransaction');
            var $submitBtn = $('#submitBtn');

            // Disable button
            $submitBtn.prop('disabled', true)
                     .text('Memproses...')
                     .addClass('opacity-70 cursor-not-allowed');

            // Pre-process form - filter out zero values
            $('.sampah-item').each(function() {
                var $item = $(this);
                var $beratHidden = $item.find('.berat-hidden');
                var berat = parseFloat($beratHidden.val()) || 0;
                
                if (berat <= 0) {
                    $item.find('input[name="sampah_id[]"]').prop('disabled', true);
                    $beratHidden.prop('disabled', true);
                }
            });

            // Submit form
            $.ajax({
                url: 'setor-sampah/store',
                type: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response && response.success && response.setorId) {
                        setTimeout(function() {
                            window.location.href = '/setor-sampah/waiting/' + response.setorId;
                        }, 1000);
                    } else {
                        console.error('Response error:', response?.message || 'Unknown error');
                        resetSubmitButton($submitBtn);
                        showErrorMessage('Terjadi kesalahan dalam memproses permintaan.');
                    }
                },
                error: function(xhr) {
                    console.error('Ajax error:', xhr.status, xhr.statusText);
                    resetSubmitButton($submitBtn);
                    
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key) && Array.isArray(errors[key])) {
                                errors[key].forEach(function(message) {
                                    showErrorMessage(message);
                                });
                            }
                        }
                    } else {
                        showErrorMessage('Terjadi kesalahan. Silakan coba lagi.');
                    }
                },
                complete: function() {
                    // Re-enable disabled form elements
                    $form.find('input:disabled').prop('disabled', false);
                }
            });
        } catch (e) {
            console.error('Error in form submission:', e);
            resetSubmitButton($('#submitBtn'));
            showErrorMessage('Terjadi kesalahan internal. Silakan refresh halaman dan coba lagi.');
        }
    }

    // Reset submit button
    function resetSubmitButton($btn) {
        try {
            if ($btn && $btn.length) {
                $btn.prop('disabled', false)
                    .text('Setor')
                    .removeClass('opacity-70 cursor-not-allowed');
            }
        } catch (e) {
            console.error('Error resetting button:', e);
        }
    }

    // Show error message
    function showErrorMessage(message) {
        try {
            if (typeof window.showToast === 'function') {
                window.showToast('error', message);
            } else {
                alert(message);
            }
        } catch (e) {
            console.error('Error showing message:', e);
            alert(message);
        }
    }

    // Start the app
    initSetorSampahApp();
})();
</script>
@endsection