@extends('layouts.layout-fe')
@section('title','')
@section('route', 'listBlog')
@section('content')
<div id="appCapsule">


    <div class="section mt-2">
        <h1>
          {{$article->title}}
        </h1>
        <div class="blog-header-info mt-2 mb-2">
            <div>
                <img src="{{ $article->user->photo ? secure_asset($article->user->photo) : secure_asset('template/assets/3135715.png') }}" alt="img" class="imaged w24 rounded me-05">
                by <a href="#">{{$article->user->name}}</a>
            </div>
            <div>
                {{$article->created_at}}
            </div>
        </div>
    </div>

    <div class="section mt-2">
        <p>
           {{$article->content}}
        </p>
        <figure>
            <img src="{{secure_asset($article->image)}}" alt="image" class="imaged img-fluid">
        </figure>
    </div>
    <div class="section">
        <button onclick="copyLink()" class="btn btn-block btn-primary">
            <ion-icon name="link-outline"></ion-icon> Copy Link
        </button>
    </div>

    
    

   

    
    
<script>
    function copyLink() {
        let postUrl = window.location.href; // Ambil URL halaman saat ini
        navigator.clipboard.writeText(postUrl).then(() => {
            alert("Link berhasil disalin! 🚀");
        }).catch(err => {
            console.error("Gagal menyalin link: ", err);
        });
    }
</script>

@endsection