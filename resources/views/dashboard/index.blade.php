@extends('layouts.layout')
@section('content')
<div class="p-3">
    <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{ number_format($totalSampah, 0, ',', '.'). 'KG'}}</h3>
                  <p>Total sampah terkumpul</p>
                </div>
                <div class="icon">
                  <i class="ion ion-trash"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{'RP'. number_format($totalSaldo, 0, ',', '.')}}</h3>
                  <p>Total Saldo Nasabah</p>
                </div>
                <div class="icon">
                  <i class="ion ion-dollar"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{'-'.'RP'. number_format($totalSaldoKeluar, 0, ',', '.')}}</h3>
                  <p>Total Saldo Keluar</p>
                </div>
                <div class="icon">
                  <i class="ion ion-dollar"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-12">
              <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Transaction ID</th>
                        <th>Nama</th>
                        <th>Sampah</th>
                        <th class="w-25 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach ($transactions as $key => $transaction)
                  <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $transaction->transaction_code }}</td>
                      <td>{{ $transaction->users->name ?? '-' }}</td>
                      <td>
                          @foreach ($transaction->details as $detail)
                              {{ $detail->sampah->nama ?? '-' }} ({{ $detail->berat }} kg) <br>
                          @endforeach
                      </td>
                      <td class="text-center">
                          <a class="btn btn-sm btn-primary" href="{{route('transaction.edit', $transaction->id)}}">Edit</a>
                          <button class="btn btn-sm btn-danger">Hapus</button>
                      </td>
                  </tr>
              @endforeach
              
                </tbody>
            </table>
            
          </div>
        
        </div><!-- /.container-fluid -->
    </section>
</div>
@endsection