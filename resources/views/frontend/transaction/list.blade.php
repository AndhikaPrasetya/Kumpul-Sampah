@extends('layouts.layout-fe')
@section('title', 'Transaksi')
@section('content')
    <div id="appCapsule">
        <div class="d-flex gap-2 mt-2" style="overflow-x: auto; margin-left:5px;">
            <div class="dropdown-status">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterStatus"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Semua status
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item filter-option" href="#" data-status="">Semua status</a>
                    <a class="dropdown-item filter-option" href="#" data-status="approved">Berhasil</a>
                    <a class="dropdown-item filter-option" href="#" data-status="rejected">Gagal</a>
                    <a class="dropdown-item filter-option" href="#" data-status="pending">Menunggu</a>
                </div>
            </div>
        </div>

        <div class="section mt-2" id="transaction-container">
            {{-- Data transaksi akan dimuat di sini --}}
        </div>
    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function() {
            function loadTransactions(status = '') {
                $.ajax({
                    url: "{{ url('/transaksi/filter') }}",
                    type: "GET",
                    data: {
                        status: status
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
                        if (data.length === 0) {
                            $("#transaction-container").html(`
                <div class="text-center">
                    <img src="{{ asset('template-fe/assets/img/empty-box.png') }}" alt="No Data" style="max-width: 200px;">
                    <p class="mt-3 text-muted">Tidak ada transaksi ditemukan.</p>
                </div>
            `);
                        } else {
                            $("#transaction-container").html(data);
                        }
                    },
                    error: function() {
                        $("#transaction-container").html(
                            '<p class="text-danger">Failed to load transactions.</p>');
                    }
                });
            }

            // Load all transactions on page load
            loadTransactions();

            // Handle filter click
            $(".filter-option").click(function(e) {
                e.preventDefault();
                let status = $(this).data("status");
                $("#filterStatus").text($(this).text());
                loadTransactions(status);
            });
        });
    </script>
@endsection
