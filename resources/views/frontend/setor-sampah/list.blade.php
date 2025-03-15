<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-gray-50 py-4 px-4 border-b border-gray-200 relative text-center">
        <a href="{{ route('transaksiFrontend.index') }}"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h1 class="text-base font-medium">Create Order</h1>
    </header>

    <div class="p-4 bg-gray-50 min-h-screen">
        <form id="createFormTransaction">
            @csrf
            <input type="hidden" name="total_amount" id="total_amount_hidden">
            <input type="hidden" name="total_points" id="total_points_hidden">

            @foreach ($kategoriSampah as $kategori)
                <div class="category-card bg-white rounded-xl shadow-sm mb-4 overflow-hidden">
                    <!-- Category Header -->
                    <div class="flex justify-between items-center p-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3 text-red-500">
                                <i class="fas fa-wine-bottle text-sm"></i>
                            </div>
                            <span class="font-medium">{{ $kategori->nama }}</span>
                        </div>
                        <i class="fas fa-chevron-up text-gray-400 toggle-icon"
                            data-category-id="{{ $kategori->id }}"></i>
                    </div>

                    <!-- Category Items -->
                    <div class="category-content" id="category-content-{{ $kategori->id }}">
                        @if (isset($groupedSampahs[$kategori->id]))
                            @foreach ($groupedSampahs[$kategori->id] as $sampah)
                                <div class="flex justify-between items-center p-3 border-b border-gray-100">
                                    <div>
                                        <div class="font-medium">{{ $sampah->nama }}</div>
                                        <div class="text-xs text-gray-400">Harga: Rp
                                            {{ number_format($sampah->harga, 0, ',', '.') }}/kg</div>
                                        <div class="text-xs text-gray-400">Points: {{ $sampah->points }}/kg</div>
                                    </div>
                                    <div class="flex items-center">
                                        <button
                                            class="w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center btn-minus">
                                            <i class="fas fa-minus text-xs text-gray-400"></i>
                                        </button>
                                        <span class="mx-3 text-sm min-w-8 text-center berat-value"
                                            data-harga="{{ $sampah->harga }}"
                                            data-points="{{ $sampah->points }}">0</span>
                                        <button
                                            class="w-7 h-7 rounded-full border border-gray-200 flex items-center justify-center btn-plus">
                                            <i class="fas fa-plus text-xs text-gray-400"></i>
                                        </button>
                                        <!-- Input tersembunyi untuk sampah_id dan berat -->
                                        <input type="hidden" name="sampah_id[]" value="{{ $sampah->id }}">
                                        <input type="hidden" name="berat[]" class="berat-hidden" value="0">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 text-center text-gray-400">
                                Tidak ada sampah dalam kategori ini.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <input type="hidden" name="total_amount_hidden" id="total_amount_hidden">
            <input type="hidden" name="total_points_hidden" id="total_points_hidden">
            <div class="btn-wrapper text-center">

                <button type="submit"
                    class="w-80 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>
            </div>
        </form>
    </div>
    <script src={{ asset('/template/plugins/jquery/jquery.min.js') }}></script>
    <script>
        $(document).ready(function() {
            // Event listener untuk ikon chevron
            $('.category-content').hide();
            $('.category-card .fa-chevron-up').on('click', function() {
                // Toggle class untuk membuka/menutup category-content
                $(this).closest('.category-card').find('.category-content').slideToggle('fast');

                // Toggle ikon chevron antara up dan down
                $(this).toggleClass('fa-chevron-up fa-chevron-down');
            });

            const handleCreateForm = (formId) => {
                const form = $(`#${formId}`);
                $.ajax({
                    url: 'setor-sampah/store',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            setTimeout(() => {
                                window.location.href = '/transaksi';
                            }, 1000);
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: (xhr) => {
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
                        $(this).find('button[type="submit"]').prop('disabled', false);

                    }
                });
            };

            // Event submit form transaksi
            $('#createFormTransaction').on('submit', function(e) {
                e.preventDefault();
                $(this).find('button[type="submit"]').prop('disabled', true);

                handleCreateForm('createFormTransaction');
            });

            // Fungsi untuk menghitung total amount dan points
            const hitungTotalAmount = () => {
                let totalAmount = 0;
                let totalPoints = 0;

                // Loop melalui setiap span berat
                $('.berat-value').each(function() {
                    const berat = parseFloat($(this).text());
                    const harga = parseFloat($(this).data('harga'));
                    const points = parseFloat($(this).data('points'));

                    if (!isNaN(berat) && berat > 0) {
                        const subtotal = harga * berat;
                        const subPoints = points * berat;
                        totalAmount += subtotal;
                        totalPoints += subPoints;
                    }
                });

                // Tampilkan total amount dan points
                $('#total_amount').val(totalAmount.toLocaleString('id-ID'));
                $('#total_amount_hidden').val(totalAmount);
                $('#total_points').val(totalPoints.toLocaleString('id-ID'));
                $('#total_points_hidden').val(totalPoints);
            };

            // Event listener untuk tombol plus
            $(document).on('click', '.btn-plus', function(e) {
                e.preventDefault(); // Mencegah tindakan default (submit form)
                const beratValue = $(this).siblings('.berat-value');
                const beratHidden = $(this).siblings('.berat-hidden');
                let berat = parseFloat(beratValue.text());
                berat += 1; // Tambah 1 kg
                beratValue.text(berat);
                beratHidden.val(berat); // Update nilai input tersembunyi
                hitungTotalAmount(); // Hitung ulang total
            });

            // Event listener untuk tombol minus
            $(document).on('click', '.btn-minus', function(e) {
                e.preventDefault(); // Mencegah tindakan default (submit form)
                const beratValue = $(this).siblings('.berat-value');
                const beratHidden = $(this).siblings('.berat-hidden');
                let berat = parseFloat(beratValue.text());
                if (berat > 0) {
                    berat -= 1; // Kurangi 1 kg
                    beratValue.text(berat);
                    beratHidden.val(berat); // Update nilai input tersembunyi
                    hitungTotalAmount(); // Hitung ulang total
                }
            });

        });
    </script>
</body>

</html>
