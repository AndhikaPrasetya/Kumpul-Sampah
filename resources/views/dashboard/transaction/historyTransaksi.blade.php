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
                            <div class="row mb-2">
                                <div class="col-12">
                                    <div id="filterContainerHistory" class="p-3 bg-light rounded" style="display: none;">
                                        <strong class="d-block mb-2">Hasil Filter:</strong> 
                                        <div class="d-flex flex-wrap mb-2">
                                            <span id="selectedNasabahFilter" class="badge badge-pill badge-info p-2 mr-1"></span>
                                            <span id="selectedRangeFilter" class="badge badge-pill badge-info p-2 mr-1"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive"> 
                                <table class="table table-bordered table-hover" id="table_history_transaction">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nasabah</th>
                                            <th>Sampah</th>
                                            <th>Berat</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
 
                                    </tbody>
                                </table>
                            </div>
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
                        <select id="nama_nasabah_filter" class="select2" multiple style="width: 100%;">
                            <option value="">Pilih Nasabah</option>
                            @foreach($nasabahs as $p)
                                <option value="{{ $p->name }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Filter Tanggal -->
                    <div class="form-group">
                        <label>Tanggal Terbit</label>
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
                            <input class="form-check-input" type="checkbox" value="transaction_id" data-column-index="1" id="check_transaction_id">
                            <label class="form-check-label" for="check_transaction_id">
                                Nasabah
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="sampah_id" data-column-index="2" id="check_sampah_id">
                            <label class="form-check-label" for="check_sampah_id">
                                Sampah
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="berat" data-column-index="3" id="check_berat">
                            <label class="form-check-label" for="check_berat">
                                Berat
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="subtotal" data-column-index="4" id="check_subtotal">
                            <label class="form-check-label" for="check_subtotal">
                                Total Harga
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="created_at" data-column-index="5" id="check_created_at">
                            <label class="form-check-label" for="check_created_at">
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

