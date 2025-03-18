<x-app-layout>

    <div class="py-6 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Update Profile Information Form -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    <div class="text-center mb-6">
                        <div class="avatar-section">
                            <a href="#">
                                <img src="{{ Auth::user()->nasabahs->first()?->photo ? asset('storage/'.Auth::user()->nasabahs->first()->photo) : asset('template/assets/3135715.png') }}" 
                                alt="avatar" class="imaged w-24 h-24 rounded-full">
                            </a>
                        </div>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
    
            <!-- Update Password Form -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" style="margin-bottom: 50px;">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
