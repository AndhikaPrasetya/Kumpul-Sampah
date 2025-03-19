<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-50 font-sans">

    <div class="p-4 min-h-screen max-w-screen-sm mx-auto" style="background-color: #f5f6f8;">
       @yield('content')
    </div>
    <script src={{ asset('/template/plugins/jquery/jquery.min.js') }}></script>
    @yield('script')
</body>

</html>
