@extends('layouts.layout')

@section('content')
<section class="content m-5">
    <div class="card card-primary">
      <div class="card-header bg-primary">
          <h3 class="card-title text-white">Edit data Sampah</h3>
      </div>
      
            <div class="card-body">
              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="nama" class="required">Nama sampah</label>
                          <input type="text" class="form-control shadow-sm" name="nama" id="nama" value="{{$data->nama}}" readonly>
                      </div>
                  </div>
                  <div class="col-12">
                      <div class="form-group">
                          <label for="category_id" class="required">Kategori sampah</label>
                         <select class="form-control" name="category_id" id="category_id" disabled>
                             <option value="" disabled selected>Pilih Kategori</option>
                          @foreach ($categories as $kategori)
                          <option value="{{$kategori->id}}" {{$data->category_id == $kategori->id ? 'selected' : '' }}>{{$kategori->nama}}</option>
                          @endforeach
                         </select>
                      </div>
                  </div>
                  <div class="col-12">
                      <div class="form-group">
                          <label for="harga" class="required">Harga per KG</label>
                          <input type="text" class="form-control shadow-sm" name="harga" id="harga" value="{{'Rp ' . number_format($data->harga, 0, ',', '.')}}" readonly>
                      </div>
                  </div>
                  <div class="col-12">
                      <div class="form-group">
                          <label for="image" class="required">Gambar sampah</label>
                          <input type="file" class="dropify" name="image"  data-default-file="{{ secure_asset($data->image) }}" readonly>
                      </div>
                  </div>
                  <div class="col-12">
                      <div class="form-group">
                          <label for="deskripsi">Deskripsi</label>
                      <textarea name="deskripsi" id="deskripsi" class="form-control" readonly>{{$data->deskripsi}}</textarea>
                  </div>
              </div>
            </div>
    
            <div class="card-footer bg-light">
              <div class="d-flex justify-content-start">
                  <button type="button" 
                          onclick="window.location.href='{{ route('sampah.index') }}'"
                          class="btn btn-warning px-4">
                      <i class="fas fa-arrow-left mr-1"></i> Back
                  </button>
              </div>
          </div>
    </div>
  </section>
@endsection