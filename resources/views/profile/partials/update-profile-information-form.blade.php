<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>
        
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil Anda.') }}
        </p>
        
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        <div>
            <x-input-label for="alamat" :value="__('alamat')" />
            <x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full" :value="old('alamat', $nasabah->alamat)" required autofocus autocomplete="alamat" />
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>
        <div>
            <x-input-label for="no_phone" :value="__('nomer handphone')" />
            <x-text-input id="no_phone" name="no_phone" type="text" class="mt-1 block w-full" :value="old('no_phone', $user->no_phone)"  autocomplete="no_phone" />
            <x-input-error class="mt-2" :messages="$errors->get('no_phone')" />
        </div>

        <div class="mt-6">
            <!-- Label dan Deskripsi -->
            <label for="photo" class="block text-sm font-medium text-gray-700">
                {{ __('Foto Profil') }}
            </label>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Anda dapat mengunggah foto profil untuk tampilan yang lebih menarik.') }}
                <span class="block text-xs text-gray-500">
                    {{ __('Ukuran yang disarankan: 200x200 piksel.') }}
                </span>
            </p>
        
            <!-- Input File dengan Tombol Custom -->
            <div class="mt-2 relative">
                <label class="inline-flex items-center cursor-pointer">
                    <!-- Input File yang Tersembunyi -->
                    <input type="file" id="photo" name="photo" accept="image/*" class="sr-only" onchange="previewFile()" />
                    
                    <!-- Tombol Custom untuk Upload -->
                    <div class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2 text-gray-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 21C16 21.5523 15.5523 22 15 22H9C8.44772 22 8 21.5523 8 21V11C8 10.4477 8.44772 10 9 10H15C15.5523 10 16 10.4477 16 11V21Z" fill="currentColor" />
                            <path d="M11 15H13V17H11V15ZM11 9H13V11H11V9ZM11 13H13V15H11V13Z" fill="currentColor" />
                        </svg>
                        {{ __('Pilih Foto Profil') }}
                    </div>
                </label>
            </div>
        
            <!-- Pratinjau Gambar & URL -->
            <div class="mt-3">
                <img id="previewImage" class="hidden w-32 h-32 object-cover rounded-md border border-gray-300">
                <p id="imageUrl" class="mt-2 text-sm text-gray-600 break-all"></p>
            </div>
        
            <!-- Pesan Error -->
            @if ($errors->has('photo'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->first('photo') }}
                </p>
            @endif
        </div>
        
        <!-- JavaScript untuk Menampilkan Pratinjau Gambar & URL -->
        <script>
            function previewFile() {
                const file = document.querySelector('#photo').files[0];
                const previewImage = document.querySelector('#previewImage');
                const imageUrlText = document.querySelector('#imageUrl');
        
                if (file) {
                    const fileURL = URL.createObjectURL(file);
                    previewImage.src = fileURL;
                    previewImage.classList.remove('hidden');
                    imageUrlText.textContent = `File URL: ${fileURL}`;
                }
            }
        </script>
        

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('script')
<script>
    function previewFile() {
        const file = document.querySelector('#photo').files[0];
        const previewImage = document.querySelector('#previewImage');
        const imageUrlText = document.querySelector('#imageUrl');

        if (file) {
            const fileURL = URL.createObjectURL(file);
            previewImage.src = fileURL;
            previewImage.classList.remove('hidden');
            imageUrlText.textContent = `File URL: ${fileURL}`;
        }
    }
</script>
@endpush
