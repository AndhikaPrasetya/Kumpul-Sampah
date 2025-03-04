@extends('layouts.layout')
@section('content')

<section class="content m-5">
  <div class="card card-primary">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">Detail Penukaran points</h3>
    </div>
          <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="user_id" class="required">Nasabah</label>
                        <select name="user_id" class="form-control" id="user_id" disabled>
                            <option value="" disabled selected>Pilih nasabah</option>
                            @foreach($nasabahs as $user)
                                <option value="{{$user->id}}" {{$penukaranPoint->user_id === $user->id ? 'selected' :''}}>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="reward_id" class="required">Rewards</label>
                        <select name="reward_id" class="form-control" id="reward_id" disabled>
                            <option value="" disabled selected>Pilih barang</option>
                            @foreach($rewards as $r)
                                <option value="{{$r->id}}" data-points="{{$r->points}}" {{$penukaranPoint->reward_id === $r->id ? 'selected' : ''}}>{{$r->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="total_points" class="required">Total Points</label>
                        <input type="text" id="total_points" class="form-control" name="total_points" readonly value="{{$penukaranPoint->total_points}}">
                    </div>
                </div>
            </div>
          </div>
  
  
          <div class="card-footer bg-light">
            <div class="d-flex justify-content-start">
                <button type="button" 
                        onclick="window.location.href='{{ route('penukaran-points.index') }}'"
                        class="btn btn-warning px-4">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </button>
            </div>
        </div>
  </div>
</section>

    </div>
@endsection

