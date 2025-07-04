<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Register</title>
    <meta name="description" content="Register Bank Imam">
    <link rel="stylesheet" href="{{ asset('template-fe/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/template/plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-floating {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 18px;
            color: #adb5bd;
            z-index: 5;
        }

        label {
            font-weight: bold;
            color: #343a40;
        }

        #appCapsule {
            padding: 40px 0;
        }

        /* Untuk mengatur lebar Select2 secara spesifik */
        .select2-container {
            width: 100% !important;
            /* Contoh: buat selebar parent-nya */
        }

        /* Untuk tinggi, Anda mungkin perlu menargetkan elemen di dalamnya */
        .select2-container .select2-selection--single {
            height: 38px;
            /* Contoh: sesuaikan dengan tinggi input form Anda */
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            /* Pastikan teks di tengah */
        }
    </style>
</head>

<body>

    <!-- App Capsule -->
    <div id="appCapsule" style="max-width: 400px; margin:0 auto;">
        <img src="{{ asset('template-fe/assets/img/protection.png') }}" alt="Announcement"
            style="width: 50%; max-width: 110px; height: auto; display: block; margin: 0 auto;">

        <div class="section text-center" style="margin-top:1em;">
            <h2>Buat Akun Sekarang!</h2>
            <p>Gabung dengan Bank Imam, wujudkan lingkungan bersih dan hijau!</p>
        </div>
        <div class="section p-3">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label for="name">Nama</label>
                                <input type="name" id="name" name="name" class="form-control shadow-sm"
                                    placeholder="name" required value="{{ old('name') }}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label for="email" :value="__('Email')">Email</label>
                                <input type="email" id="email" name="email" class="form-control  shadow-sm"
                                    placeholder="Email" required value="{{ old('email') }}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="bsu_id">BSU</label>
                                <select class="form-control custom-select" name="bsu_id" id="bsu_id">
                                    <option value="" disabled selected>Pilih BSU</option>
                                    @foreach ($bsu as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control  shadow-sm"
                                    placeholder="Password" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label for="password_confirmation" :value="__('Confirm Password')">Konfirmasi
                                    Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control  shadow-sm" placeholder="Konfirmasi Password" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                    </div>
                    <div class=" transparent text-center" style="margin-top: 2em;">
                        <button type="submit" class=" btn btn-primary btn-block btn-lg mb-1">Register </button>
                        <p>Sudah Punya Akun ? <a href="{{ route('login') }}">Klik Disini</a></p>
                    </div>
                </div>

            </form>
        </div>

    </div>
    <script src="{{ asset('/template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.custom-select').select2();
        });
    </script>
</body>

</html>
