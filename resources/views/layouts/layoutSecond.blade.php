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
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-gray-50 py-4 px-4 border-b border-gray-200 relative text-center max-w-screen-sm mx-auto">
        <a href="{{ route('transaksiFrontend.index') }}"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700">
            <i class="fas fa-chevron-left"></i>
        </a>
        <h1 class="text-base font-medium">@yield('title', 'Default Title')</h1>
    </header>

    <div class="p-4 min-h-screen max-w-screen-sm mx-auto" style="background-color: #f5f6f8;">
       @yield('content')
    </div>
    <script src={{ asset('/template/plugins/jquery/jquery.min.js') }}></script>
    <script src="{{asset('template/plugins/toastr/toastr.min.js')}}"></script>
    @yield('script')
</body>

</html>
