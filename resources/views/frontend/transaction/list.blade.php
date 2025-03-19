@extends('layouts.layout-fe')
@section('title', 'Riwayat transaksi')
@section('content')
    <div id="appCapsule" style="max-width: 640px; margin:0 auto; background-color: #f5f6f8; ">
        <div class="d-flex gap-2 mt-2" style="overflow-x: auto; margin-left:5px;">
            <div class="dropdown-status">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterStatus"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Semua status
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item filter-status" href="#" data-status="">Semua status</a>
                    <a class="dropdown-item filter-status" href="#" data-status="approved">Berhasil</a>
                    <a class="dropdown-item filter-status" href="#" data-status="rejected">Gagal</a>
                    <a class="dropdown-item filter-status" href="#" data-status="pending">Menunggu</a>
                </div>
            </div>
            <div class="dropdown-tanggal">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDays"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Semua tanggal
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item filter-date" href="#" data-days="">Semua tanggal</a>
                    <a class="dropdown-item filter-date" href="#" data-days="30">30 hari terakhir</a>
                    <a class="dropdown-item filter-date" href="#" data-days="90">90 hari terakhir</a>
                    <a class="dropdown-item filter-date-custom" href="#">Pilih tanggal sendiri</a>
                </div>
                <input type="date" id="custom-date" class="form-control d-none custom-date" />
            </div>
            <div class="dropdown-transaksi">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterTransaksi"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Semua transaksi
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item filter-transaksi" href="#" data-type="">Semua transaksi</a>
                    <a class="dropdown-item filter-transaksi" href="#" data-type="tarik_tunai">Tarik tunai</a>
                    <a class="dropdown-item filter-transaksi" href="#" data-type="setor_sampah">Setor sampah</a>
                    <a class="dropdown-item filter-transaksi" data-type="tukar_points" href="#">Penukaran poin</a>
                </div>
            </div>

        </div>
      
        <div class="section mt-2"  style="min-height: 520px;" i id="transaction-container">
            {{-- Data transaksi akan dimuat di sini --}}
           
        </div>
    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function() {
            let selectedStatus = "";
            let selectedDays = "";
            let selectedTransaksi = "";

            function fetchTransactions() {
                $.ajax({
                    url: "{{ url('/transaksi/filter') }}",
                    type: "GET",
                    data: {
                        status: selectedStatus,
                        days: selectedDays,
                        type: selectedTransaksi
                    },
                    beforeSend: function() {
                        $("#transaction-container").html(`
                    <div class="text-center">
                        <div class="text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading...</p>
                    </div>
                `);
                    },
                    success: function(data) {
                        if ($.trim(data) === "" || data.length === 0) {
                            $("#transaction-container").html(`
                        <div class="text-center">
                           <img src="{{ secure_asset('template-fe/assets/img/empty-box.png') }}" alt="No Data" style="max-width: 200px;">
                            <p class="mt-3 text-muted">Tidak ada transaksi ditemukan.</p>
                        </div>
                    `);
                        } else {
                            $("#transaction-container").html(data);
                        }
                    },
                    error: function() {
                        $("#transaction-container").html(
                            '<p class="text-danger text-center">Gagal memuat transaksi.</p>');
                    }
                });
            }

            fetchTransactions();

            // Event Listener untuk Filter Status
            $(".filter-status").click(function(e) {
                e.preventDefault();
                selectedStatus = $(this).data("status");
                $("#filterStatus").text($(this)
                    .text()); // Tambahkan teks yang diklik ke elemen dengan ID filterStatus
                fetchTransactions();
            });


            // Event Listener untuk Filter Tanggal
            $(".filter-date").click(function(e) {
                e.preventDefault();
                selectedDays = $(this).data("days");
                $("#filterDays").text($(this).text());
                $("#custom-date").addClass("d-none"); // Sembunyikan input date jika dipilih opsi default
                fetchTransactions();
            });

            // Event Listener untuk Pilih Tanggal Sendiri
            $(".filter-date-custom").click(function(e) {
                e.preventDefault();
                $("#custom-date").removeClass("d-none").focus(); // Tampilkan input date
            });

            // Event Listener untuk Input Date
            $("#custom-date").change(function() {
                selectedDays = $(this).val(); // Ambil tanggal yang dipilih
                fetchTransactions();
            });

            // Event Listener untuk Filter Transaksi
            $(".filter-transaksi").click(function(e) {
                e.preventDefault();
                selectedTransaksi = $(this).data("type");
                $("#filterTransaksi").text($(this)
                    .text()); // Tambahkan teks yang diklik ke elemen dengan ID filterStatus
                fetchTransactions();
            });




        });
    </script>
@endsection
