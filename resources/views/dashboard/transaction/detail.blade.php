@extends('layouts.layout')

@section('content')
<section class="content m-5">
    <div class="card card-primary">
      <div class="card-header bg-primary">
          <h3 class="card-title text-white">Detail Transaksi</h3>
      </div>
            <div class="card-body">
              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="user_id">Nasabah</label>
                         <select name="user_id" class="form-control" id="user_id" disabled>
                          <option value="" disabled selected>Pilih nasabah</option>
                          @foreach($users as $user)
                          <option value="{{$user->id}}" {{ $user->id == $selectedUserIds ? 'selected' : '' }}>{{$user->name}}</option>
                          @endforeach
                         </select>
                      </div>
                  </div>
              </div>
                 
  
              <div id="dynamic-input-sampah">
                @foreach($transactionDetails as $key => $detail)
                <div class="row align-items-center input-group-sampah">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="sampah_id">Sampah</label>
                            <input type="hidden" name="id_detail[]" value="{{ $detail->id }}">
                            <select name="sampah_id[]" class="form-control sampah-select" disabled>
                                <option value="" disabled>Pilih sampah</option>
                                @foreach($sampahs as $sampah)
                                <option value="{{$sampah->id}}" data-harga="{{$sampah->harga}}" 
                                    {{ $sampah->id == $detail->sampah_id ? 'selected' : '' }}>
                                    {{$sampah->nama}} - Rp {{number_format($sampah->harga, 0, ',', '.')}}/kg
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label for="berat">Berat</label>
                            <div class="input-group">
                                <input type="text" class="form-control berat-input" name="berat[]" 
                                    value="{{ $detail->berat }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
              </div>
             
              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="total_amount">Total Amount</label>
                          <input type="text" class="form-control shadow-sm" name="total_amount" id="total_amount" value="{{number_format($transaction->total_amount, 0, ',','.')}}" readonly>
                          <input type="hidden" name="total_amount_hidden" id="total_amount_hidden" >
                      </div>
                  </div>
              </div>
              
            </div>
    
            <div class="card-footer bg-light">
              <div class="d-flex justify-content-start">
                  <button type="button" 
                          onclick="window.location.href='{{ route('transaction.index') }}'"
                          class="btn btn-warning px-4">
                      <i class="fas fa-arrow-left mr-1"></i> Back
                  </button>
              </div>
          </div>
    </div>
</section>
@endsection