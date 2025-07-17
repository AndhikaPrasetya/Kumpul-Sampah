@extends('layouts.layout')
@section('content')
    <section class="content m-5">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Details Nasabah</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control shadow-sm" name="name" id="name"
                                value="{{ $bsu->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control shadow-sm" name="email" id="email"
                                value="{{ $bsu->email }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="no_phone">Nomer Handphone</label>
                            <input type="text" class="form-control shadow-sm" name="no_phone" id="no_phone"
                                placeholder="0812XXXXXX" value="{{$bsu->no_phone}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="rt">RT</label>
                            <input type="text" class="form-control shadow-sm" name="rt" id="rt" value="{{$bsuDetail->rt}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="rw">RW</label>
                            <input type="text" class="form-control shadow-sm" name="rw" id="rw" value="{{$bsuDetail->rw}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan</label>
                            <input type="text" class="form-control shadow-sm" name="kelurahan" id="kelurahan" value="{{$bsuDetail->kelurahan}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control shadow-sm" name="alamat" id="alamat"
                        placeholder="Jl.kayu" value="{{$bsuDetail->alamat}}" readonly>
                    </div>
                </div>
            </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-start">
                        <button type="button" 
                                onclick="window.location.href='{{ route('bsu.index') }}'"
                                class="btn btn-warning px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </button>
                    </div>
                </div>
        </div>
    </section>
@endsection

