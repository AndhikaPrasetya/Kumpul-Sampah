@extends('layouts.layout-fe')
@section('title', 'Profile')
  @section('content')
  <div id="appCapsule">

    <div class="section mt-3 text-center">
        <div class="avatar-section">
            <a href="#">
                <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('template/assets/3135715.png') }}" alt="avatar" class="imaged w100 rounded">
                <span class="button">
                    <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="camera outline"></ion-icon>
                </span>
            </a>
        </div>
    </div>
    <div class=" mt-4 listview image-listview text inset">
        <div class="card-body">

            <div class="form-group boxed">
                <div class="input-wrapper">
                   <label for="name">Nama</label>
                    <input type="name" id="name" name="name" class="form-control shadow-sm" value="{{ $user->name }}" >
                   
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                   <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control shadow-sm"
                        placeholder="Email" value="{{ $user->email }}" >
                   
                </div>
            </div>
        </div>
    </div>
</div>
  @endsection  