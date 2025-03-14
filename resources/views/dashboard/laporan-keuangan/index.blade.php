{{-- @extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-sm-6">
                    <h1 class="text-dark" style="font-size: calc(1.2rem + 0.6vw);">{{ $title }}</h1>
                </div>
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 mt-2 mt-sm-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">{{ $breadcrumb }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="d-flex justify-content-end border-bottom p-3">
                            <div class="mr-2">
                                <button type="button" class="btn btn-sm btn-success mb-3" data-toggle="modal"
                                    data-target="#exportDataModal">
                                    <i class="fas fa-file-export"></i> Export Data
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary mb-3" data-toggle="modal"
                                    data-target="#filterModalHistory">
                                    <i class="fas fa-filter"></i> Filter Data
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover " id="table_laporan">
                                <thead>
                                    <tr>
                                        <th>Nasabah</th>
                                        <th>Sampah</th>
                                        <th>Berat</th>
                                        <th>Pendapatan</th>
                                        <th>Points</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                             
                            </table>
                            


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="filterModalHistory" tabindex="-1" role="dialog" aria-labelledby="filterModalHistoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalHistoryLabel">Filter Data</h5>
                    <a href="#" class="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama nasabah</label>
                        <select id="nama_nasabah_filter" class="select-history" multiple style="width: 100%;">
                            <option value="">Pilih Nasabah</option>
                            @foreach($nasabahs as $p)
                                <option value="{{ $p->name }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Filter Tanggal -->
                    <div class="form-group">
                        <label>Tanggal </label>
                        <input type="text" class="form-control" id="daterange">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="reset_filter">Reset Filter</button>
                    <button type="button" class="btn btn-primary" id="apply_filter">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
<!-- Modal Export Data -->
<div class="modal fade" id="exportDataModal" tabindex="-1" role="dialog" aria-labelledby="exportDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportDataModalLabel">Export Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pilih kolom untuk di export:</label>
                    <div class="checkbox-list">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="transaction_id" data-column-index="0" id="check_transaction_id">
                            <label class="form-check-label" for="check_transaction_id">
                                Nasabah
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="sampah_id" data-column-index="1" id="check_sampah_id">
                            <label class="form-check-label" for="check_sampah_id">
                                Sampah
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="berat" data-column-index="2" id="check_berat">
                            <label class="form-check-label" for="check_berat">
                                Berat
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="subtotal" data-column-index="3" id="check_subtotal">
                            <label class="form-check-label" for="check_subtotal">
                                Total Pendapatan
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="points" data-column-index="4" id="check_points">
                            <label class="form-check-label" for="check_points">
                                Points
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="tanggal" data-column-index="5" id="check_tanggal">
                            <label class="form-check-label" for="check_tanggal">
                                Tanggal
                            </label>
                        </div>
                    </div>
                </div>
                <div class="export-buttons mt-3">
                    <button type="button" class="btn btn-success btn-sm" id="exportExcel">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="exportPDF">
                        <i class="fas fa-file-pdf"></i> Export to PDF
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <style>
        /* Responsive styles */
        @media (max-width: 768px) {
            .content-header {
                padding: 15px;
            }

            .content-header h1 {
                margin-bottom: 8px;
            }

            .breadcrumb {
                margin-bottom: 0;
                padding: 0;
                font-size: 14px;
            }

            .btn-kategori {
                width: 100%;
            }

            .card {
                margin-bottom: 1rem;
            }

            .card-header {
                padding: 12px;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }

            .input-group {
                width: 100% !important;
                margin-top: 8px;
            }

            .table {
                font-size: 14px;
            }

            .table td,
            .table th {
                padding: 8px;
            }

            /* Action buttons container */
            .table .action-buttons {
                display: flex;
                gap: 4px;
                justify-content: center;
            }

            /* Make action buttons more touch-friendly */
            .table .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                margin: 2px;
            }
        }

        /* Additional improvements for very small screens */
        @media (max-width: 375px) {
            .content-header h1 {
                font-size: 1.5rem;
            }

            .table td,
            .table th {
                padding: 6px;
                font-size: 13px;
            }

            .table .btn {
                padding: 0.2rem 0.4rem;
                font-size: 0.8rem;
            }
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#filterLaporan').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('laporan-keuangan.filter') }}", // Ganti dengan route yang sesuai
                    type: "GET",
                    data: {
                        bulan: $('#bulan').val(),
                        tahun: $('#tahun').val()
                    },
                    success: function(response) {
                        $('#nasabah').text(response.total_nasabah);
                        $('#sampah').text(response.total_sampah);
                        $('#saldo').text("Rp " + new Intl.NumberFormat().format(response
                            .total_saldo));
                    }
                });
            });
        });
    </script>
@endsection --}}

@extends('layouts.layout')
@section('content')
<section class="content">
    <div class="container-fluid">
       <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="mb-4">Pilih Laporan</h3>
        
                    <!-- Form Pilihan -->
                    <form id="reportForm" class="mb-4">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Mulai:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label>Tanggal Akhir:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>
                        <div class="form-wrapper text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="report_type" id="transactionReport" value="transaction" checked>
                                <label class="form-check-label" for="transactionReport">
                                    Laporan Transaksi
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="report_type" id="withdrawReport" value="withdraw">
                                <label class="form-check-label" for="withdrawReport">
                                    Laporan Withdraw
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Tampilkan</button>
                        </div>
                    </form>
                
                    <!-- Tabel akan dimuat di sini -->
                    <div id="reportResult">
                       
                    </div>
                </div>
            </div>
        </div>
       </div>
    </div>
</section>

@endsection
@section('script')
<script>
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let reportType = document.querySelector('input[name="report_type"]:checked').value;
        let startDate = document.getElementById('start_date').value;
        let endDate = document.getElementById('end_date').value;

        let url = "{{ route('laporan-keuangan.getReport') }}" + "?report_type=" + reportType + "&start_date=" + startDate + "&end_date=" + endDate;

        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('reportResult').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });
</script>
@endsection
