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
                                <div class="d-flex justify-content-center mt-3">

                                    <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
                                    <button id="exportToPDF" class="btn btn-success d-none">Export ke PDF</button>
                                </div>
                            </div>
                        </form>
                    
                        <!-- Tabel akan dimuat di sini -->
                        <div id="reportResult"></div>

                        
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

        if(!startDate || !endDate){
            alert('Pilih tanggal terlebih dahulu');
            return;
        }

        $('#exportToPDF').removeClass('d-none');
        
        let url = "{{ route('laporan-keuangan.getReport') }}" + "?report_type=" + reportType + "&start_date=" + startDate + "&end_date=" + endDate;

        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('reportResult').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });

    // Menambahkan event untuk tombol export ke PDF
    document.getElementById('exportToPDF').addEventListener('click', function() {
        let reportType = document.querySelector('input[name="report_type"]:checked').value;
        let startDate = document.getElementById('start_date').value;
        let endDate = document.getElementById('end_date').value;

        let url = "{{ route('laporan-keuangan.exportReport') }}" + "?report_type=" + reportType + "&start_date=" + startDate + "&end_date=" + endDate;

        window.location.href = url;  // Mengarahkan ke URL export PDF
    });
</script>
@endsection
