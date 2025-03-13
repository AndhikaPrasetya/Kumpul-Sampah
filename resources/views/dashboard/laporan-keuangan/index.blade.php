@extends('layouts.layout')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-sm-6">
                    <h1 class="text-dark" style="font-size: calc(1.2rem + 0.6vw);">{{$title}}</h1>
                </div>
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 mt-2 mt-sm-0">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">{{$breadcrumb}}</li>
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
                                <button type="button" class="btn btn-sm btn-success mb-3" data-toggle="modal" data-target="#exportDataModal">
                                    <i class="fas fa-file-export"></i> Export Data
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#filterModalHistory">
                                    <i class="fas fa-filter"></i> Filter Data
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="filterLaporan">
                                <label for="bulan">Bulan:</label>
                                <select name="bulan" id="bulan">
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                </select>
                            
                                <label for="tahun">Tahun:</label>
                                <select name="tahun" id="tahun">
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                </select>
                            
                                <button type="submit">Tampilkan Laporan</button>
                            </form>
                            
                            <div id="laporanResult">
                                <p><strong>Total Nasabah Setor:</strong> <span id="nasabah">-</span></p>
                                <p><strong>Total Sampah (Kg):</strong> <span id="sampah">-</span></p>
                                <p><strong>Total Saldo (Rp):</strong> <span id="saldo">-</span></p>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

        .btn-kategori{
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
                $('#saldo').text("Rp " + new Intl.NumberFormat().format(response.total_saldo));
            }
        });
    });
});

    </script>
@endsection

