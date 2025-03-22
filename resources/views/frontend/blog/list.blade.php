@extends('layouts.layout-fe',['noBottomMenu' => true])
@section('title', 'Berita')
@section('content')
<div id="appCapsule" style="max-width:640px; margin:0 auto;">
    @if($heroNews)
    <div class="section full p-2 mt-2 mb-2">
        <a href="{{route('detailBlog',$heroNews->slug)}}">
        <div class="card shadow-sm">
            <img src="{{ asset($heroNews->thumbnail) }}" class="card-img-top img-fluid"  alt="Hero Image">
            <div class="card-body">
                <h5 class="card-title">{{ $heroNews->title }}</h5>
                <p class="card-text">{{ Str::limit($heroNews->content, 100) }}</p>
            </div>
        </div>
        </a>
    </div>
    @endif

    <div class="section tab-content mt-2 mb-2">

        <div class="row">
            @foreach ($otherNews as $news)
            <div class="col-12 mb-2">
                <a href="{{route('detailBlog',$news->slug)}}">
                    <div class="blog-card p-1 d-flex">
                        <img src="{{ asset($news->thumbnail) }}" class="imaged w86">
                        <div class="text">
                            <h5 class="card-title">{{ $news->title }}</h5>
                            <p class="card-text">{{ Str::limit($news->content, 80) }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

    </div>

</div>
@endsection