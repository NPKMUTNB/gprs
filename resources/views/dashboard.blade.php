<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Welcome Message --}}
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->name }}!</h3>
                <p class="text-gray-600 mt-1">
                    @if($role === 'student')
                        Manage your projects and track their progress.
                    @elseif($role === 'advisor')
                        Review and manage projects assigned to you.
                    @elseif($role === 'committee')
                        Evaluate student projects and provide feedback.
                    @elseif($role === 'admin')
                        Monitor system activity and manage users.
                    @endif
                </p>
            </div>

            {{-- Student Dashboard --}}
            @if($role === 'student')
                @include('dashboard.partials.student')
            @endif

            {{-- Advisor Dashboard --}}
            @if($role === 'advisor')
                @include('dashboard.partials.advisor')
            @endif

            {{-- Committee Dashboard --}}
            @if($role === 'committee')
                @include('dashboard.partials.committee')
            @endif

            {{-- Admin Dashboard --}}
            @if($role === 'admin')
                @include('dashboard.partials.admin')
            @endif
        </div>
    </div>
</x-app-layout>
