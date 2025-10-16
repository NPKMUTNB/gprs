<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
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
            <x-input-label for="department" :value="__('Department')" />
            <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" :value="old('department', $user->department)" autocomplete="organization" />
            <x-input-error class="mt-2" :messages="$errors->get('department')" />
        </div>

        <div>
            <x-input-label for="profile_pic" :value="__('Profile Picture')" />
            
            @if ($user->profile_pic)
                <div class="mt-2 mb-3">
                    <p class="text-sm text-gray-600 mb-2">{{ __('Current Profile Picture:') }}</p>
                    <img src="{{ asset('storage/' . $user->profile_pic) }}" 
                         alt="{{ $user->name }}" 
                         class="w-32 h-32 rounded-full object-cover border-2 border-gray-300"
                         id="current-profile-pic">
                </div>
            @endif

            <div class="mt-2">
                <input type="file" 
                       id="profile_pic" 
                       name="profile_pic" 
                       accept="image/jpeg,image/png,image/jpg,image/gif"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100"
                       onchange="previewProfilePicture(event)">
                <p class="mt-1 text-sm text-gray-500">{{ __('JPG, PNG, GIF up to 2MB') }}</p>
            </div>

            <div id="preview-container" class="mt-3 hidden">
                <p class="text-sm text-gray-600 mb-2">{{ __('Preview:') }}</p>
                <img id="preview-image" 
                     src="" 
                     alt="Preview" 
                     class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('profile_pic')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

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

    <script>
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('preview-container');
                    const previewImage = document.getElementById('preview-image');
                    const currentPic = document.getElementById('current-profile-pic');
                    
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    
                    if (currentPic) {
                        currentPic.style.opacity = '0.5';
                    }
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</section>
