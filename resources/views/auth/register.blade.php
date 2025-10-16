<x-guest-layout>
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('auth.register_title') }}</h2>
        <p class="mt-2 text-sm text-gray-600">{{ __('auth.register_subtitle') }}</p>
    </div>

    <!-- Student Registration Notice -->
    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-medium">{{ app()->getLocale() == 'th' ? 'การลงทะเบียนสำหรับนักศึกษา' : 'Student Registration' }}</p>
                <p class="mt-1">{{ app()->getLocale() == 'th' ? 'หน้านี้สำหรับนักศึกษาเท่านั้น สำหรับอาจารย์และกรรมการ กรุณาติดต่อผู้ดูแลระบบ' : 'This registration is for students only. For advisors and committee members, please contact the system administrator.' }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('auth.name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('auth.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role (Hidden - Student Only) -->
        <input type="hidden" name="role" value="student" />

        <!-- Department -->
        <div class="mt-4">
            <x-input-label for="department" :value="__('auth.department')" />
            <x-text-input id="department" class="block mt-1 w-full" type="text" name="department" :value="old('department')" autocomplete="organization" />
            <x-input-error :messages="$errors->get('department')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('auth.confirm_password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('auth.register') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <span class="text-sm text-gray-600">{{ __('auth.already_registered') }}</span>
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                {{ __('auth.log_in') }}
            </a>
        </div>
    </form>
</x-guest-layout>
