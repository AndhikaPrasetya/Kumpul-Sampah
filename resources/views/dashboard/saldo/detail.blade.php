@extends('layouts.layout')
@section('content')

<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">View saldo</h3>
    </div>
      <form id="updateFormSaldo" data-id="{{ $saldo->id }}">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="user_id" class="required">Nasabah</label>
                        <select class="form-control" name="user_id" id="user_id" disabled>
                            <option value="" disabled selected>Pilih Kategori</option>
                         @foreach ($nasabahs as $nasabah)
                         <option value="{{$nasabah->id}}" {{$saldo->user_id == $nasabah->id ? 'selected' : ''}}>{{$nasabah->name}}</option>
                         @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="balance" class="required">Balance</label>
                        <input type="text" class="form-control shadow-sm" name="balance" id="balance" value="{{ number_format($saldo->balance, 0, ',', '.');}}" readonly>
                    </div>
                </div>
                
            </div>
          
          </div>
  
          <div class="card-footer bg-light">
            <div class="d-flex justify-content-start">
                <button type="button" 
                        onclick="window.location.href='{{ route('saldo.index') }}'"
                        class="btn btn-warning px-4">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </button>
            </div>
        </div>
      </form>
  </div>
</section>

    </div>
@endsection