@extends('layouts.layoutMain', ['noBottomMenu' => true])
@section('headTitle', 'Detail Berita')
@section('title', 'Detail Berita')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> 
    @if ($article->image)
    <div class="mb-6 rounded-xl overflow-hidden shadow-lg"> 
        <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="w-full h-64 sm:h-80 object-cover"
            onerror="this.src='{{ asset('images/default-blog-image.jpg') }}'">
    </div>
    @endif

    <div class="mb-6">
        <h1 class="text-sm font-extrabold text-gray-900 leading-tight mb-4">
            {{ $article->title }}
        </h1>

        <div class="flex items-center text-sm text-gray-600 mb-6">
            <img src="{{ $article->user->photo ? asset($article->user->photo) : asset('images/default-avatar.png') }}"
                 alt="Author Photo" class="w-8 h-8 rounded-full object-cover mr-2">
            <span class="font-medium text-gray-800">by {{ $article->user->name }}</span>
            <span class="ml-auto text-gray-500">{{ $article->created_at->format('d M, Y') }}</span>
        </div>
    </div>

    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-8"> 
        {!! $article->content !!}
    </div>
    <div class="mt-8 mb-4">
        <button onclick="copyLink()" class="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5.656 10.828a2 2 0 11-2.828-2.828l3-3a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 005.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5z" clip-rule="evenodd" />
            </svg>
            <span>Copy Link</span>
        </button>
    </div>

</div>
@endsection
@section('scripts')
    
<script>
    function copyLink() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            alert('Link berhasil disalin!');
            // Anda bisa mengganti alert dengan notifikasi Toast/Snackbar yang lebih elegan
        }).catch(err => {
            console.error('Gagal menyalin link: ', err);
            alert('Gagal menyalin link.');
        });
    }
</script>
@endsection
