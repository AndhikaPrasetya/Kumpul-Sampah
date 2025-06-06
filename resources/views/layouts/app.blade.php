<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Profile</title>

    <link rel="stylesheet" href="{{asset('/template/plugins/fontawesome-free/css/all.min.css')}}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen max-w-screen-sm mx-auto bg-gray-100">
        <header class="bg-gray-50 py-4 px-4 border-b border-gray-200 relative text-center max-w-screen-sm mx-auto">
            <a href="{{ route('home') }}"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h1 class="text-base font-medium">{{ $title ?? 'Profile' }}</h1>
        </header>

        <main class="max-w-screen-sm">
            {{ $slot }}
        </main>

        @if (!isset($noBottomMenu) || !$noBottomMenu)
        <div class="max-w-screen-sm fixed bottom-0 left-0 right-0 w-full mx-auto bg-white border-t border-gray-300 flex items-center justify-around py-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center text-gray-600 {{ Route::is('home') ? 'text-green-500 font-bold' : '' }}">
                <ion-icon name="home-outline" class="text-2xl"></ion-icon>
                <span class="text-xs">Home</span>
            </a>
            <a href="{{ route('listBlog') }}" class="flex flex-col items-center text-gray-600 {{ Route::is('listBlog') ? 'active' : '' }}">
                <ion-icon name="newspaper-outline" class="text-2xl"></ion-icon>
                <span class="text-xs">Info</span>
            </a>
            <a href="{{route('leaderboard')}}" class="flex flex-col items-center text-gray-600 {{ Route::is('leaderboard') ? 'active' : '' }}">
                <ion-icon name="podium" class="text-2xl"></ion-icon>
                <span class="text-xs">Peringkat</span>
            </a>
            <a href="/profile" class="flex flex-col items-center {{ Request::is('profile') ? 'text-green-500 font-bold' : 'text-gray-600' }}">
                <ion-icon name="person-circle-outline" class="text-2xl"></ion-icon>
                <span class="text-xs">Profile</span>
            </a>
        </div>
        @endif
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
