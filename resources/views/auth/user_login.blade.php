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
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.css"
        integrity="sha512-pmAAV1X4Nh5jA9m+jcvwJXFQvCBi3T17aZ1KWkqXr7g/O2YMvO8rfaa5ETWDuBvRq6fbDjlw4jHL44jNTScaKg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Swiper container */
        .swiper-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Slide styling */
        .swiper-slide {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }

        /* Gambar dalam slide */
        .illustration-container img {
            width: 80%;
            max-width: 300px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Kontainer teks */
        .content-container {
            margin-top: 1em;
            padding: 20px;
            text-align: center;
        }

        .content-container h2 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .content-container p {
            font-size: 0.9rem;
            color: #555;
        }

        /* Tombol Register & Login */
        .carousel-button-footer {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            margin: 0 auto;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .button-group a,
        .button-group button {
            flex: 1;
            text-align: center;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            border: none;
        }

        .btn-outline-secondary {
            background-color: white;
            border: 1px solid #ccc;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        /* Responsive styling */
        @media (max-width: 480px) {
            .content-container h2 {
                font-size: 1rem;
            }

            .content-container p {
                font-size: 0.8rem;
            }

            .button-group a,
            .button-group button {
                font-size: 0.9rem;
                padding: 10px;
            }

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
                        <img src="{{ asset('template-fe/assets/img/tukar_sampah.svg') }}" alt="Announcement"
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
                        <img src="{{ asset('template-fe/assets/img/tukar.svg') }}" alt="Announcement"
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
                    <a href="{{ route('register') }}"
                        class="btn btn-outline-secondary btn-lg btn-block skip-intro">Register</a>
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
                                <input type="email" id="email" name="email"
                                    class="form-control shadow-sm {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    placeholder="Email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper position-relative">
                                <label for="password">Password</label>

                                <input type="password" id="password" name="password"
                                    class="form-control shadow-sm {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password" value="{{ old('password') }}" required>

                                <button type="button"
                                    class="btn btn-sm btn-link position-absolute top-50 end-0 me-1 p-0"
                                    onclick="togglePassword()">
                                    <ion-icon id="toggleIcon" name="eye-outline"
                                        style="font-size: 20px; color:#16a34a"></ion-icon>
                                </button>
                            </div>
                                <a href="{{ route('password.request') }}">Lupa Password?</a>
                        </div>
                    </div>
                    <div class=" transparent text-center" style="margin-top: 2em;">
                        <button type="submit" class="btn btn-primary btn-block btn-lg mb-1 fw-bold">Log in</button>
                        <div>
                            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a> </p>
                        </div>
                        <div>
                        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"
        integrity="sha512-Ysw1DcK1P+uYLqprEAzNQJP+J4hTx4t/3X2nbVwszao8wD+9afLjBQYjz7Uk4ADP+Er++mJoScI42ueGtQOzEA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                console.log("Intro sudah pernah dilihat, langsung tampilkan app.");
                $("#appCapsule").show();
            } else {
                console.log("Intro belum pernah dilihat, tampilkan onboarding.");
                $(".onboarding-container").show();
                $("#appCapsule").hide();
            }
        });

        $(".skip-intro").click(function() {
            localStorage.setItem("introSeen", "true");
            $(".onboarding-container").fadeOut(500, function() {
                $("#appCapsule").fadeIn(500);
            });
        });

        $(document).ready(function() {
            // Ambil elemen
            const passwordInput = $('#password');
            const toggleIcon = $('#toggleIcon');

            // Fungsi untuk mengontrol tampilan ikon
            function checkPasswordInput() {
                if (passwordInput.val().length > 0) {
                    toggleIcon.show(); // Tampilkan ikon jika ada input
                } else {
                    toggleIcon.hide(); // Sembunyikan ikon jika tidak ada input
                }
            }

            // Jalankan fungsi saat halaman pertama kali dimuat (untuk kasus old('password'))
            checkPasswordInput();

            // Dengarkan event 'input' pada bidang kata sandi
            passwordInput.on('input', function() {
                checkPasswordInput();
            });

            // Fungsi togglePassword Anda yang sudah ada
            function togglePassword() {
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    toggleIcon.attr('name', 'eye-off-outline');
                } else {
                    passwordInput.attr('type', 'password');
                    toggleIcon.attr('name', 'eye-outline');
                }
            }

            // Buat agar fungsi togglePassword bisa diakses global oleh onclick="togglePassword()"
            window.togglePassword = togglePassword;
        });
    </script>

</body>

</html>
