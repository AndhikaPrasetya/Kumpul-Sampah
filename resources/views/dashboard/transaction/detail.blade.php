@extends('layouts.layout')

@section('content')
  <section class="content m-5">
      <div class="card card-primary">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white">Detail Kategori</h3>
        </div>
        
              <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="nama">Nama Kategori</label>
                                <input type="text" class="form-control shadow-sm" name="nama" id="nama" value="{{$data->nama}}" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <input type="text" class="form-control shadow-sm" name="deskripsi" id="deskripsi" value="{{$data->deskripsi}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="card-footer bg-light">
                <div class="d-flex justify-content-start">
                    <button type="button" 
                            onclick="window.location.href='{{ route('kategori-sampah.index') }}'"
                            class="btn btn-warning px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </button>
                </div>
            </div>
          
      </div>
  </section>
@endsection