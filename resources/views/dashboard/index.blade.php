@extends('layouts.layout')
@section('content')
    <div class="p-3">
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                  <div class="col-lg-3 col-12">
                      <div class="small-box bg-info">
                          <div class="inner">
                              <h3>{{ number_format($totalSampah, 0, ',', '.') . 'KG' }}</h3>
                              <p>Total sampah terkumpul</p>
                          </div>
                          <div class="icon">
                              <i class="fas fa-dumpster"></i>
                          </div>
                          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
                  <div class="col-lg-3 col-12">
                      <div class="small-box bg-success">
                          <div class="inner">
                              <h3>{{ 'RP' . number_format($totalSaldo, 0, ',', '.') }}</h3>
                              <p>Total Saldo Nasabah</p>
                          </div>
                          <div class="icon">
                              <i class="fas fa-wallet"></i>
                          </div>
                          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
                  <div class="col-lg-3 col-12">
                      <div class="small-box bg-danger">
                          <div class="inner">
                              <h3>{{ '-' . 'RP' . number_format($totalSaldoKeluar, 0, ',', '.') }}</h3>
                              <p>Total Saldo Keluar</p>
                          </div>
                          <div class="icon">
                              <i class="fas fa-money-bill-wave"></i>
                          </div>
                          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
                  <div class="col-lg-3 col-12">
                      <div class="small-box bg-primary">
                          <div class="inner">
                              <h3>{{ number_format($totalNasabah, 0, ',', '.') }}</h3>
                              <p>Total Nasabah</p>
                          </div>
                          <div class="icon">
                              <i class="fas fa-users"></i>
                          </div>
                          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      </div>
                  </div>
              </div>
                <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                              <i class="fas fa-trash nav-icon"></i>
                              Data Sampah</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="sampahChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
                <div class="col-12 col-lg-6">
                  <div class="card card-primary card-outline">
                      <div class="card-header">
                          <h3 class="card-title">
                              <i class="fas fa-users nav-icon"></i> Data Nasabah Setor
                          </h3>
              
                          <div class="card-tools d-flex align-items-center">
                              <select id="filterYear" class="form-control form-control-sm mr-2">
                                  <option value="">Semua Tahun</option>
                                  <script>
                                      let currentYear = new Date().getFullYear();
                                      for (let year = currentYear; year >= currentYear - 5; year--) {
                                          document.write(`<option value="${year}">${year}</option>`);
                                      }
                                  </script>
                              </select>
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                  <i class="fas fa-times"></i>
                              </button>
                          </div>
                      </div>
                      <div class="card-body">
                          <canvas id="nasabahChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                      </div>
                      <!-- /.card-body -->
                  </div>
              </div>
              

                </div>

            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let sampahData = @json($sampahData);

            // Ambil labels dan jumlah dari data Laravel
            let labels = sampahData.map(item => item.category_name || "Tidak Diketahui");
            let values = sampahData.map(item => item.jumlah);

            // Warna untuk setiap kategori (pastikan cukup untuk jumlah kategori)
            let backgroundColors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#ff6384',
                '#36a2eb', '#cc65fe', '#ffce56'
            ];

            var donutChartCanvas = $('#sampahChart').get(0).getContext('2d');
            var donutData = {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors.slice(0, labels
                        .length) // Ambil warna sesuai jumlah kategori
                }]
            };

            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // ============= Total Nasabah CHART ============================//
            var nasabahData = @json(array_values($nasabahData)); // Data jumlah per bulan
            var bulanLabels = @json(array_values($bulanLabels)); // Label bulan

            var barChartCanvas = document.getElementById('nasabahChart').getContext('2d');

            var barChartData = {
                labels: bulanLabels,
                datasets: [{
                    label: 'Jumlah Nasabah per Bulan',
                    data: nasabahData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,

                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }

                    }
                }
            });
        });
    </script>
@endsection
