<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('headTitle', 'Default Title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('template/plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('template/plugins/swiper-11.2.6/package/swiper-bundle.min.css')}}">
    
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    @if (!isset($noHeader) || !$noHeader)
    <header class="bg-gray-50 py-4 px-4 border-b border-gray-200 relative text-center max-w-screen-sm mx-auto">
        <a href="{{ $route }}"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h1 class="text-base font-medium">@yield('title', 'Default Title')</h1>
    </header>
    @endif

    <div class="p-1 min-h-screen max-w-screen-sm mx-auto" style="background-color: #f5f6f8;">
       @yield('content')
    </div>

    @if (!isset($noBottomMenu) || !$noBottomMenu)
    <div class="max-w-screen-sm fixed bottom-0 left-0 right-0 z-50 w-full mx-auto bg-white border-t border-gray-300 flex items-center justify-around py-2">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-gray-600 {{ Route::is('home') ? 'text-green-500 font-bold' : '' }}">
            <ion-icon name="home-outline" class="text-2xl"></ion-icon>
            <span class="text-xs">Home</span>
        </a>
        <a href="{{ route('listBlog') }}" class="flex flex-col items-center text-gray-600 {{ Route::is('listBlog') ? 'text-green-500 font-bold' : '' }}"">
            <ion-icon name="newspaper-outline" class="text-2xl"></ion-icon>
            <span class="text-xs">Info</span>
        </a>
        <a href="{{route('leaderboard')}}" class="flex flex-col items-center text-gray-600 {{ Route::is('leaderboard') ? 'text-green-500 font-bold' : '' }}"">
            <ion-icon name="podium" class="text-2xl"></ion-icon>
            <span class="text-xs">Peringkat</span>
        </a>
        <a href="/profile" class="flex flex-col items-center {{ Request::is('profile') ? 'text-green-500 font-bold' : 'text-gray-600' }}">
            <ion-icon name="person-circle-outline" class="text-2xl"></ion-icon>
            <span class="text-xs">Profile</span>
        </a>
    </div>
    @endif

    

    <script src="{{ asset('/template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/plugins/swiper-11.2.6/package/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('/template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
     <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
     <script src="{{asset('template-fe/assets/js/plugins/splide/splide.min.js')}}"></script>

    @yield('script')
</body>

</html>
