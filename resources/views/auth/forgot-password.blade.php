{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Lupa Password</title>
    <meta name="description" content="Lupa password Bank Imam">
    <link rel="stylesheet" href="{{ asset('template-fe/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{asset('/template/plugins/fontawesome-free/css/all.min.css')}}">



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
        label{
            font-weight: bold;
            color: #343a40;
        }
        #appCapsule{
            padding: 40px 0;
        }
        
    </style>
</head>

<body>

    <!-- App Capsule -->
    <div id="appCapsule" style="max-width: 400px; margin:0 auto;">
        <img src="{{ asset('template-fe/assets/img/lupa-password.png') }}" alt="Announcement"
        style="width: 50%; max-width: 110px; height: auto; display: block; margin: 0 auto;">

        <div class="section text-center" style="margin-top:1em;">
            <h2>Lupa Kata Sandi?</h2>
            <p>Jangan khawatir! Masukkan email Anda untuk mendapatkan tautan reset kata sandi dan kembali mengakses akun Anda. 🌱</p>
        </div>
        <div class="section p-3" >
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div>
                    <div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label for="email" >Email</label>
                                <input type="name" id="email" name="email" class="form-control shadow-sm"
                                    placeholder="email" required value="{{old('email')}}">
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                    </div>
                    <div class=" transparent d-flex justify-content-center" style="margin-top: 2em;">
                        <button type="submit" class=" btn btn-primary btn-block btn-lg">Email Password Reset Link </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
    <!-- * App Capsule -->


</body>

</html>

