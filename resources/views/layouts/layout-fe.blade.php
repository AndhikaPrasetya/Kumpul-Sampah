<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Bank Imam</title>
    <meta name="description" content="Bank imam">
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="{{asset('template-fe/assets/css/style.css')}}">
    @livewireStyles
</head>

<body>

    <!-- loader -->
    <div id="loader">
        <img src="{{asset('template-fe/assets/img/bank.svg')}}" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

  
        <!-- App Header -->
        <div class="appHeader" style="max-width: 640px; margin:0 auto;">
            <div class="left">
                <a href="{{ route(View::yieldContent('route', 'home')) }}" class="headerButton">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                </a>
                
            </div>
            <div class="pageTitle">@yield('title', 'Default Title')</div>
           
        </div>
        <!-- * App Header -->


  @yield('content')
  @livewireScripts


    <!-- App Bottom Menu -->
    <div class="appBottomMenu" style="max-width: 640px; margin:0 auto;">
        <a href="{{route('home')}}" class="item">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="{{route('listBlog')}}" class="item  {{ Route::is('listBlog') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="newspaper-outline"></ion-icon>
                <strong>Info</strong>
            </div>
        </a>
        <a href="app-components.html" class="item">
            <div class="col">
                <ion-icon name="podium"></ion-icon>
                <strong>Peringkat</strong>
            </div>
        </a>
        <a href="{{route('profile.edit')}}" class="item {{ Route::is('profile.edit') ? 'active' : '' }}">
            <div class="col">
                <ion-icon name="person-circle-outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->






    <!-- Android Add to Home Action Sheet -->
    <div class="modal inset fade action-sheet android-add-to-home" id="android-add-to-home-screen" tabindex="-1"
        role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Home Screen</h5>
                    <a href="#" class="close-button" data-bs-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content text-center">
                        <div class="mb-1">
                            <img src="assets/img/icon/192x192.png" alt="image" class="imaged w64 mb-2">
                        </div>
                        <div>
                            Install <strong>Finapp</strong> on your Android's home screen.
                        </div>
                        <div>
                            Tap <ion-icon name="ellipsis-vertical"></ion-icon> and Add to homescreen.
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-primary btn-block" data-bs-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Android Add to Home Action Sheet -->



    <div id="cookiesbox" class="offcanvas offcanvas-bottom cookies-box" tabindex="-1" data-bs-scroll="true"
        data-bs-backdrop="false">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">We use cookies</h5>
        </div>
        <div class="offcanvas-body">
            <div>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla non lacinia quam. Nulla facilisi.
                <a href="#" class="text-secondary"><u>Learn more</u></a>
            </div>
            <div class="buttons">
                <a href="#" class="btn btn-primary btn-block" data-bs-dismiss="offcanvas">I understand</a>
            </div>
        </div>
    </div>

    <!-- ========= JS Files =========  -->
    <script src={{asset('/template/plugins/jquery/jquery.min.js')}}></script>
    <script src="{{asset('template-fe/assets/js/lib/bootstrap.bundle.min.js')}}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="{{asset('template-fe/assets/js/plugins/splide/splide.min.js')}}"></script>
    <!-- Base Js File -->
    <script src="{{asset('template-fe/assets/js/base.js')}}"></script>

    <script>
        // Add to Home with 2 seconds delay.
        AddtoHome("2000", "once");
    </script>
    @yield('script')

</body>

</html>