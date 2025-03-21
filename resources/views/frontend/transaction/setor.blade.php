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
            <input type="hidden" name="total_amount" id="total_amount_hidden">
            <input type="hidden" name="total_points" id="total_points_hidden">

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
                                <div class="flex items-center">
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
// Ensure jQuery is loaded before executing code
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }

    // Wait for jQuery to be fully initialized
    $(function() {
        // Initialize weight buttons
        initWeightButtons();
        
        // Form submit event
        $('#createFormTransaction').on('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = $('#submitBtn');
            disableSubmitButton(submitBtn);

            handleCreateForm('createFormTransaction');
        });

        // Event toggle category with event delegation
        $(document).on('click', '.toggle-icon', function() {
            const categoryId = $(this).data('category-id');
            const categoryContent = $(`#category-content-${categoryId}`);

            if (categoryContent.length > 0) {
                categoryContent.slideToggle('fast');
                $(this).toggleClass('fa-chevron-up fa-chevron-down');
            }
        });
    });
});

// Function to handle form submission
function handleCreateForm(formId) {
    const form = $(`#${formId}`);

    if (!form.length) {
        console.error(`Form dengan ID ${formId} tidak ditemukan.`);
        enableSubmitButton($('#submitBtn'));
        return;
    }

    // Remove zero weight inputs before submitting
    removeZeroWeightInputs(form);

    $.ajax({
        url: 'setor-sampah/store',
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response && response.success) {
                if (response.setorId) {
                    setTimeout(() => {
                        window.location.href = `/setor-sampah/waiting/${response.setorId}`;
                    }, 1000);
                } else {
                    console.error('setorId tidak ditemukan dalam response.');
                    enableSubmitButton($('#submitBtn'));
                }
            } else {
                console.error('Error:', response?.message || 'Response tidak valid');
                enableSubmitButton($('#submitBtn'));
            }
        },
        error: function(xhr) {
            handleAjaxError(xhr);
            enableSubmitButton($('#submitBtn'));
        }
    });
}

// Function for plus and minus buttons
function initWeightButtons() {
    $(document).on('click', '.btn-plus', function(e) {
        e.preventDefault();
        updateWeight($(this), 1);
    });

    $(document).on('click', '.btn-minus', function(e) {
        e.preventDefault();
        updateWeight($(this), -1);
    });
}

// Function to update weight
function updateWeight(button, change) {
    const beratValue = button.siblings('.berat-value');
    const beratHidden = button.siblings('.berat-hidden');
    let berat = parseFloat(beratValue.text()) || 0;

    berat += change;
    if (berat < 0) berat = 0;

    beratValue.text(berat);
    beratHidden.val(berat);
    hitungTotalAmount();
}

// Function to remove inputs with weight 0
function removeZeroWeightInputs(form) {
    if (!form || !form.find) {
        console.error('Invalid form element:', form);
        return;
    }
    
    const beratInputs = form.find('.berat-hidden');
    if (!beratInputs.length) {
        console.warn('No weight inputs found in the form.');
        return;
    }
    
    beratInputs.each(function() {
        const beratVal = parseFloat($(this).val());

        if (!isNaN(beratVal) && beratVal === 0) {
            const sampahIdInput = $(this).siblings('input[name="sampah_id[]"]');

            if (sampahIdInput.length > 0) {
                sampahIdInput.remove();
            }
            $(this).remove();
        }
    });
}

// Function to disable submit button
function disableSubmitButton(button) {
    if (!button || !button.length) return;
    
    button.prop('disabled', true);
    button.text('Memproses...');
    button.addClass('opacity-70 cursor-not-allowed');
}

// Function to enable submit button
function enableSubmitButton(button) {
    if (!button || !button.length) return;
    
    button.prop('disabled', false);
    button.text('Setor');
    button.removeClass('opacity-70 cursor-not-allowed');
}

// Function to handle AJAX errors
function handleAjaxError(xhr) {
    if (!xhr) {
        showToast('error', 'Unknown error occurred');
        return;
    }
    
    if (xhr.status === 422) {
        const errors = xhr.responseJSON?.errors;

        if (errors && typeof errors === 'object' && !Array.isArray(errors)) {
            let errorCount = 0;
            Object.entries(errors).forEach(([field, messages]) => {
                if (Array.isArray(messages)) {
                    messages.forEach(message => {
                        if (typeof showToast === 'function') {
                            showToast('error', message);
                        } else {
                            console.error('Validation error:', message);
                        }
                        errorCount++;
                    });
                } else if (typeof messages === 'string') {
                    if (typeof showToast === 'function') {
                        showToast('error', messages);
                    } else {
                        console.error('Validation error:', messages);
                    }
                    errorCount++;
                }
            });
            
            if (errorCount === 0) {
                if (typeof showToast === 'function') {
                    showToast('error', 'Terjadi kesalahan validasi.');
                } else {
                    console.error('Validation error occurred');
                }
            }
        } else {
            if (typeof showToast === 'function') {
                showToast('error', 'Terjadi kesalahan validasi.');
            } else {
                console.error('Validation error occurred');
            }
        }
    } else {
        const errorMessage = xhr.responseJSON?.error || 'Terjadi kesalahan tidak diketahui.';
        if (typeof showToast === 'function') {
            showToast('error', errorMessage);
        } else {
            console.error('Error:', errorMessage);
        }
    }
}

// Function to calculate total amount, points, and weight
function hitungTotalAmount() {
    let totalAmount = 0;
    let totalPoints = 0;
    let totalBerat = 0;

    $('.berat-value').each(function() {
        const beratText = $(this).text().trim();
        const berat = beratText ? parseFloat(beratText) : 0;
        const harga = $(this).data('harga') !== undefined ? parseFloat($(this).data('harga')) : 0;
        const points = $(this).data('points') !== undefined ? parseFloat($(this).data('points')) : 0;

        if (!isNaN(berat) && berat > 0) {
            totalAmount += harga * berat;
            totalPoints += points * berat;
            totalBerat += berat;
        }
    });

    $('#total_amount_hidden').val(totalAmount);
    $('#total_points_hidden').val(totalPoints);
    $('.total-berat').text(`${totalBerat} KG`);
}

// Helper function to check if showToast exists and provide fallback
function showToast(type, message) {
    if (typeof window.showToast === 'function') {
        window.showToast(type, message);
    } else {
        if (type === 'error') {
            console.error(message);
            alert(message);
        } else {
            console.log(message);
        }
    }
}
</script>
@endsection