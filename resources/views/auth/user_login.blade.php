<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>Login</title>
    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('template-fe/assets/css/style.css') }}">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
        }

        .onboarding-container {
            max-width: 400px;
            height: 100vh;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            background-color: white;
            position: relative;
            overflow: hidden;
        }

        .illustration-container {
            position: relative;
            height: 80%;
            background: linear-gradient(to bottom, #a1d9ff 0%, #f0f4f8 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .illustration-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            z-index: 10;
        }

        .content-container {
            margin-top: 1em;
            padding: 20px;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .get-started-btn {
            background-color: #2e8B57;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
        }

        #appCapsule {
            
            display: none;
            padding: 20px;
        }
    </style>
</head>

<body>

    <!-- Onboarding Screen -->
    <div class="onboarding-container" class="bg-white">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="illustration-container">
                        <img src="{{ asset('template-fe/assets/img/bank.svg') }}" alt="Announcement"
                            style="width: 78%; max-width: 300px; height: auto; display: block; margin: 0 auto;">
                    </div>
                    <div class="content-container">
                        <div>
                            <h2>Selamat Datang di Bank Imam!</h2>
                            <p>Mulai langkah kecil Anda untuk menjaga lingkungan dengan cara yang mudah dan modern. Daur
                                ulang sampah jadi lebih praktis dan transparan.</p>
                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="illustration-container">
                        <img src="{{ asset('template-fe/assets/img/tukar_sampah.svg') }}" alt="Announcement"
                            style="width: 78%; max-width: 300px; height: auto; display: block; margin: 0 auto;">
                    </div>
                    <div class="content-container">
                        <div>
                            <h2>Tukar Sampah <br>
                                Dapatkan Manfaat!</h2>
                            <p>Setiap sampah yang Anda kumpulkan memiliki nilai. Tukarkan sampah anorganik Anda menjadi
                                poin atau saldo yang bisa digunakan untuk berbagai keperluan sehari-hari.</p>
                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="illustration-container">
                        <img src="{{ asset('template-fe/assets/img/langkah_mudah.svg') }}" alt="Announcement"
                            style="width: 78%; max-width: 300px; height: auto; display: block; margin: 0 auto;">
                    </div>
                    <div class="content-container">
                        <div>
                            <h2>Langkah Mudah, Dampak Besar!</h2>
                            <p>Dengan 3 langkah sederhana: Pilah, Kumpulkan, dan Setor, Anda sudah berkontribusi untuk
                                lingkungan yang lebih bersih dan hijau. Ayo mulai sekarang!</p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="swiper-pagination" style="margin-bottom: 5em;"></div>

        </div>
        <div class="carousel-button-footer" style="max-width: 400px; margin:0 auto;">
            <div class="row">
                <div class="col-6">
                    <a href="{{route('register')}}" class="btn btn-outline-secondary btn-lg btn-block skip-intro">Register</a>
                </div>
                <div class="col-6">
                    <button class="btn btn-primary btn-lg btn-block skip-intro ">Login</button>
                </div>
            </div>
        </div>

    </div>

    

    <!-- Form Login -->
    <div id="appCapsule" style="max-width: 400px;  margin: 0 auto;">
        
            <img src="{{ asset('template-fe/assets/img/login.svg') }}" alt="Announcement"
                style="width: 78%; max-width: 300px; height: 350px; display: block; margin: 0 auto;">


        <div class="section p-2">

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <h2>Selamat datang kembali!</h2>
                    <div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                               <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Email" value="{{ old('email') }}" required autofocus>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                              <label for="Password">Password</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Password" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class=" transparent text-center" style="margin-top: 2em;">
                        <button type="submit" class="btn btn-primary btn-block btn-lg mb-1 fw-bold">Log in</button>
                        <div>
                            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a> </p>
                        </div>
                        {{-- <div> 
                    @if (Route::has('password.request'))
                      <a href="{{ route('password.request') }}">Forgot your password?</a>
                    @endif
                  </div> --}}
                    </div>
                </div>


            </form>
        </div>

    </div>

    <script src={{ asset('/template/plugins/jquery/jquery.min.js') }}></script>
    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="{{ asset('template-fe/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="{{ asset('template-fe/assets/js/plugins/splide/splide.min.js') }}"></script>
    <!-- Base Js File -->
    <script src="{{ asset('template-fe/assets/js/base.js') }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var swiper = new Swiper('.swiper-container', {
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                }
            });

            // Cek jika user sudah melihat intro sebelumnya
            if (localStorage.getItem("introSeen")) {
                $(".onboarding-container").hide();
                $("#appCapsule").show();
            }

            // Saat tombol "Lewati" ditekan
            $(".skip-intro").click(function() {
                localStorage.setItem("introSeen", "true");
                $(".onboarding-container").fadeOut(500, function() {
                    $("#appCapsule").fadeIn(500);
                });
            });
        });
    </script>

</body>

</html>
