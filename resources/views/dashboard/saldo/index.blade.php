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
                        @can('create saldo')
                        <div class="card-header bg-transparent border-bottom p-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <a href="{{ route('saldo.create') }}" class="btn btn-primary btn-sm btn-kategori">
                                    <i class="fas fa-plus"></i> Tambah Saldo
                                </a>
                            </div>
                        </div>
                        @endcan
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered  table-hover" id="table_saldo">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nasabah</th>
                                            <th>Saldo masuk</th>
                                            <th>Saldo keluar</th>
                                            <th>Saldo akhir</th>
                                            <th class="text-center" style="width: 20%;">Action</th>
                                        </tr>
                                    </thead>
                                </table>
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

