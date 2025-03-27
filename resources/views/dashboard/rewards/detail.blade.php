@extends('layouts.layout')
@section('content')

<section class="content m-5">
    <div class="card card-primary">
      <div class="card-header bg-primary">
          <h3 class="card-title text-white">Edit Rewards</h3>
      </div>
            <div class="card-body">
              <div class="row">
                  <div class="col-12 col-md-3">
                      <div class="form-group">
                          <label for="name" class="required">Nama barang</label>
                          <input type="text" class="form-control shadow-sm" name="name" id="name" value="{{$rewards->name}}" readonly>
                      </div>
                  </div>
  
                  <div class="col-12 col-md-3">
                      <div class="form-group">
                          <label for="points" class="required">Jumlah poin</label>
                          <input type="text" class="form-control shadow-sm" name="points" id="points" value="{{$rewards->points}}" readonly>
                      </div>
                  </div>
                  <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="stok" class="required">Jumlah Stok</label>
                        <input type="text" class="form-control shadow-sm" name="stok" id="stok" value="{{$rewards->stok}}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="tanggal_expired" class="required">Batas Waktu</label>
                        <input type="date" class="form-control shadow-sm" name="tanggal_expired" id="tanggal_expired" value="{{ number_format($rewards->tanggal_expired, 0, ',', '.');}}" readonly>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="deskripsi" class="required">Deskripsi</label>

                    <textarea name="deskripsi" readonly disabled> {{$rewards->deskripsi}}</textarea>
                    </div>
                </div>
                  <div class="col-12">
                      <div class="form-group">
                          <label for="image" class="required">Gambar barang</label>
  
                          <input type="file" class="dropify shadow-sm" name="image" id="image" data-default-file="{{ asset($rewards->image) }}" disabled>
                      </div>
                  </div>
                  
              </div>
            </div>
    
            <div class="card-footer bg-light">
              <div class="d-flex justify-content-start">
                  <button type="button" 
                          onclick="window.location.href='{{ route('rewards.index') }}'"
                          class="btn btn-warning px-4">
                      <i class="fas fa-arrow-left mr-1"></i> Back
                  </button>
              </div>
          </div>
    </div>
  </section>
@endsection