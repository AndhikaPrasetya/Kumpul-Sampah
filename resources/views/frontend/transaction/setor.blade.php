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
                                    <button class="w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center btn-minus">
                                        <i class="fas fa-minus text-xs text-gray-400"></i>
                                    </button>
                                    <span class="mx-3 text-sm min-w-8 text-center berat-value" data-harga="{{ $sampah->harga }}" data-points="{{ $sampah->points }}">0</span>
                                    <button class="w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center btn-plus">
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
    $(document).ready(function() {
    // Inisialisasi
    initCategoryToggle();
    initFormSubmission();
    initWeightButtons();

    // Fungsi untuk toggle kategori
    function initCategoryToggle() {
    console.log('Menginisialisasi category toggle...');

    // Menyembunyikan semua kategori awalnya
    $('.category-content').hide();

    // Event toggle untuk setiap chevron
    $('.toggle-icon').on('click', function () {
        console.log('Chevron diklik:', this);

        const categoryId = $(this).data('category-id');
        const categoryContent = $('#category-content-' + categoryId);

        if (categoryContent.length > 0) {
            console.log('Menampilkan/Menyembunyikan kategori:', categoryId);
            categoryContent.slideToggle('fast');
            $(this).toggleClass('fa-chevron-up fa-chevron-down');
        } else {
            console.warn('Tidak ada category-content untuk kategori ini.');
        }
    });
}


    // Fungsi untuk handle form submission
    function initFormSubmission() {
        $('#createFormTransaction').on('submit', function(e) {
            e.preventDefault();
            const submitBtn = $('#submitBtn');
            disableSubmitButton(submitBtn); // Nonaktifkan tombol submit
            handleCreateForm('createFormTransaction');
        });
    }

    // Fungsi untuk handle tombol plus dan minus
    function initWeightButtons() {
        $(document).on('click', '.btn-plus', function(e) {
            e.preventDefault();
            updateWeight($(this), 1); // Tambah 1 kg
        });

        $(document).on('click', '.btn-minus', function(e) {
            e.preventDefault();
            updateWeight($(this), -1); // Kurangi 1 kg
        });
    }

    // Fungsi untuk update berat
    function updateWeight(button, change) {
        const beratValue = button.siblings('.berat-value');
        const beratHidden = button.siblings('.berat-hidden');
        let berat = parseFloat(beratValue.text()) || 0;

        berat += change; // Tambah atau kurangi berat
        if (berat < 0) berat = 0; // Pastikan berat tidak negatif

        beratValue.text(berat);
        beratHidden.val(berat);
        hitungTotalAmount(); // Hitung ulang total
    }

    // Fungsi untuk handle AJAX form submission
    function handleCreateForm(formId) {
        const form = $(`#${formId}`);
        removeZeroWeightInputs(form); // Hapus input dengan berat 0

        $.ajax({
            url: 'setor-sampah/store',
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    setTimeout(() => {
                        window.location.href = '/setor-sampah/waiting/' + response.setorId;
                    }, 1000);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                handleAjaxError(xhr);
            }
        });
    }

    // Fungsi untuk menghapus input dengan berat 0
    function removeZeroWeightInputs(form) {
        form.find('.berat-hidden').each(function() {
            if (parseFloat($(this).val()) === 0) {
                $(this).siblings('input[name="sampah_id[]"]').remove();
                $(this).remove();
            }
        });
    }

    // Fungsi untuk menonaktifkan tombol submit
    function disableSubmitButton(button) {
        button.prop('disabled', true);
        button.text('Memproses...');
        button.addClass('opacity-70 cursor-not-allowed');
    }

    // Fungsi untuk handle error AJAX
    function handleAjaxError(xhr) {
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            $.each(errors, (field, messages) => {
                messages.forEach(message => {
                    showToast('error', message);
                });
            });
        } else {
            showToast('error', xhr.responseJSON.error);
        }
        $('#submitBtn').prop('disabled', false); // Aktifkan kembali tombol submit
    }

    // Fungsi untuk menghitung total amount, points, dan berat
    function hitungTotalAmount() {
    let totalAmount = 0;
    let totalPoints = 0;
    let totalBerat = 0;

    $('.berat-value').each(function() {
        const beratText = $(this).text().trim();
        const berat = beratText ? parseFloat(beratText) : 0;
        const harga = $(this).data('harga') ? parseFloat($(this).data('harga')) : 0;
        const points = $(this).data('points') ? parseFloat($(this).data('points')) : 0;

        if (!isNaN(berat) && berat > 0) {
            totalAmount += harga * berat;
            totalPoints += points * berat;
            totalBerat += berat;
        }
    });

    // Update nilai di form
    $('#total_amount_hidden').val(totalAmount);
    $('#total_points_hidden').val(totalPoints);
    $('.total-berat').text(totalBerat + " KG");
}

});
    </script>

@endsection
