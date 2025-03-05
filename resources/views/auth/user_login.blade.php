

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
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        #onboarding-screen {
            position: fixed;
            width: 100%;
            height: 100vh;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 1000;
        }

        #login-form {
            display: none;
            padding: 20px;
        }

        .swiper-container {
            width: 90%;
            height: 70vh;
        }

        button {
            padding: 10px 20px;
            background: #5dc664;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Onboarding Screen -->
    <div id="onboarding-screen" class="bg-white">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide p-3">
                    <div class="svg-wrapper mb-3">
                        <img src="{{ asset('template-fe/assets/img/plant.png') }}"
                            alt="Announcement"
                            style="width: 78%; max-width: 300px; height: auto; display: block; margin: 0 auto;">

                    </div>

                    <h2>Selamat Datang di Bank Imam!</h2>
                    <p>Mulai langkah kecil Anda untuk menjaga lingkungan dengan cara yang mudah dan modern. Daur
                        ulang sampah jadi lebih praktis dan transparan.</p>
                </div>
                <div class="swiper-slide p-2">
                  <div class="svg-wrapper mb-3">
                    <img src="{{ asset('template-fe/assets/img/plant.png') }}"
                        alt="Announcement"
                        style="width: 78%; max-width: 300px; height: auto; display: block; margin: 0 auto;">

                </div>
                    <h2>Tukar Sampah <br>
                        Dapatkan Manfaat!</h2>
                    <p>Setiap sampah yang Anda kumpulkan memiliki nilai. Tukarkan sampah anorganik Anda menjadi poin atau saldo yang bisa digunakan untuk berbagai keperluan sehari-hari.</p>
                  
                </div>
                <div class="swiper-slide p-2">
                  <div class="svg-wrapper mb-3">
                    <img src="{{ asset('template-fe/assets/img/plant.png') }}"
                        alt="Announcement"
                        style="width: 78%; max-width: 300px; height: auto; display: block; margin: 0 auto;">

                </div>
                  <h2>Langkah Mudah, Dampak Besar!</h2>
                  <p>Dengan 3 langkah sederhana: Pilah, Kumpulkan, dan Setor, Anda sudah berkontribusi untuk lingkungan yang lebih bersih dan hijau. Ayo mulai sekarang!</p>
                  
                </div>
            </div>
            <div class="swiper-pagination"></div>
          
            <button type="button" id="skip-intro" class="btn btn-outline-success shadowed me-1 mb-1">Lewati</button>
           
        </div>
        
    </div>

    <!-- Form Login -->
    <div id="appCapsule">
      <img src="{{ asset('template-fe/assets/img/plant.png') }}"
      alt="Announcement"
      style="width: 78%; max-width: 300px; height: auto; display: block; margin: 0 auto;">

      <div class="section mb-5 p-2">

        <form method="POST" action="{{ route('login') }}">
          @csrf
              <div>
                  <div>
                   
                      <div class="form-group boxed">
                        <div class="input-wrapper">
                          <label class="label" for="email1">E-mail</label>
                          <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                      <div class="form-group boxed">
                        <div class="input-wrapper">
                          <label class="label" for="password">Password</label>
                          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                  </div>
                  <div class=" transparent">
                    <button type="submit" class="btn btn-primary btn-block btn-lg mb-1">Log in</button>
                    <div>
                      <p >Belum punya akun? <a href="{{ route('register') }}">Daftar</a> </p>
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
                $("#onboarding-screen").hide();
                $("#login-form").show();
            }

            // Saat tombol "Lewati" ditekan
            $("#skip-intro").click(function() {
                localStorage.setItem("introSeen", "true");
                $("#onboarding-screen").fadeOut(500, function() {
                    $("#login-form").fadeIn(500);
                });
            });
        });
    </script>

</body>

</html>

