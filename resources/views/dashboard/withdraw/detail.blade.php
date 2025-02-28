@extends('layouts.layout')
@section('content')
<section class="content m-5">
    <div class="card card-primary">
      <div class="card-header bg-primary">
          <h3 class="card-title text-white">View detail penarikan</h3>
      </div>
            <div class="card-body">
              <div class="row">
                  <div class="col-12 col-md-6">
                      <div class="form-group">
                          <label for="user_id">Nasabah</label>
                          <select class="form-control" name="user_id" id="user_id" disabled>
                              <option value="" disabled selected>Pilih Kategori</option>
                           @foreach ($nasabahs as $nasabah)
                           <option value="{{$nasabah->id}}" {{$data->user_id == $nasabah->id ? 'selected' : ''}}>{{$nasabah->name}}</option>
                           @endforeach
                          </select>
                      </div>
                  </div>
  
                  <div class="col-12 col-md-6">
                      <div class="form-group">
                          <label for="amount">amount</label>
                          <input type="text" class="form-control shadow-sm" name="amount" id="amount" readonly
                              value="{{number_format($data->amount, 0, ',', '.')}}">
  
                      </div>
                  </div>
  
              
                          <div class="col-12 col-md-6">
                              <div class="form-group">
                                  <label for="status">status</label>
                                  <select name="status" class="form-control" disabled>
                                      <option value="pending" {{$data->status === 'pending' ? 'selected' : ''}}>menunggu</option>
                                      <option value="rejected" {{$data->status === 'rejected' ? 'selected' : ''}}>tolak</option>
                                      <option value="approved" {{$data->status === 'approved' ? 'selected' : ''}}>approved</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-12 col-md-6">
                              <div class="form-group">
                                  <label for="image">Bukti pencairan</label>
                                  <div class="img-wrapper">
                                      @if ($data->image)
                                      <a href="{{ asset('storage/' . $data->image) }}" target="_blank">
                                          <img src="{{ asset('storage/' . $data->image) }}" alt="Bukti Transfer" width="100">
                                      </a>
                                  @else
                                      <span class="text-danger">Belum ada bukti</span>
                                      @endif

                                  </div>
                              </div>
                          </div>
                              
                        
                  
              </div>
            
            </div>
    
            <div class="card-footer bg-light">
              <div class="d-flex justify-content-start">
                  <button type="button" 
                          onclick="window.location.href='{{ route('withdraw.index') }}'"
                          class="btn btn-warning px-4">
                      <i class="fas fa-arrow-left mr-1"></i> Back
                  </button>
              </div>
          </div>
    </div>
  </section>
@endsection