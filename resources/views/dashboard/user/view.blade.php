@extends('layouts.layout')
@section('content')
    <section class="content m-5">
        <div class="card card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Details User</h3>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control shadow-sm" name="name" id="name"
                                    value="{{ $data->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control shadow-sm" name="email" id="email"
                                    value="{{ $data->email }}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="roles">Roles</label>
                                <select @cannot('create-user') disabled @endcannot class="allRole" name="roles[]"
                                    multiple="multiple" style="width: 100%;" disabled>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ in_array($role, $userRole) ? 'selected' : '' }}>
                                            {{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="no_phone">Nomer Handphone</label>
                                <input type="text" class="form-control shadow-sm" name="no_phone" id="no_phone"
                                    placeholder="0812XXXXXX" value="{{$data->no_phone}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control shadow-sm" name="alamat" id="alamat"
                            placeholder="Jl.kayu" value="{{$data->alamat}}" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="photo">Foto Profile</label>
                                <div class="img-wrapper mb-3">
                                    @if (!empty($data->photo))
                                        <img src="{{ asset($data->photo) }}" alt="image" width="100px">
                                    @else
                                        <p><i>Anda belum mengupload foto</i></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-start">
                        <button type="button" 
                                onclick="window.location.href='{{ route('users.index') }}'"
                                class="btn btn-warning px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </button>
                    </div>
                </div>
        </div>
    </section>
@endsection

