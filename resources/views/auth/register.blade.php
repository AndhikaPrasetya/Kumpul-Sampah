{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required style="height:55px;" autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
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
    <title>Register</title>
    <meta name="description" content="Register Bank Imam">
    <link rel="stylesheet" href="{{ asset('template-fe/assets/css/style.css') }}">
    <link rel="stylesheet" href={{asset('/template/plugins/fontawesome-free/css/all.min.css')}}>



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
        <img src="{{ asset('template-fe/assets/img/protection.png') }}" alt="Announcement"
        style="width: 50%; max-width: 110px; height: auto; display: block; margin: 0 auto;">

        <div class="section text-center" style="margin-top:1em;">
            <h2>Buat Akun Sekarang!</h2>
            <p>Gabung dengan Bank Imam, wujudkan lingkungan bersih dan hijau!</p>
        </div>
        <div class="section p-3" >
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label for="name" >Nama</label>
                                <input type="name" id="name" name="name" class="form-control shadow-sm"
                                    placeholder="name" required value="{{old('name')}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                               <label for="email" :value="__('Email')" >Email</label>
                                <input type="email" id="email" name="email" class="form-control  shadow-sm"
                                    placeholder="Email" required value="{{old('email')}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                              <label for="password_confirmation" :value="__('Confirm Password')">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control  shadow-sm"
                                    placeholder="Konfirmasi Password" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated"
                                        aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                    </div>
                    <div class=" transparent d-flex justify-content-center" style="margin-top: 2em;">
                        <button type="submit" class=" btn btn-primary btn-block btn-lg">Register </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
    <!-- * App Capsule -->


</body>

</html>
