<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('messages.dashboard') }}
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->isStudent())
                            <x-nav-link :href="route('projects.index', ['created_by' => Auth::id()])" :active="request()->routeIs('projects.*') && request('created_by') == Auth::id()">
                                {{ __('messages.my_projects') }}
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->isAdvisor())
                            <x-nav-link :href="route('projects.index', ['advisor_id' => Auth::id()])" :active="request()->routeIs('projects.*') && request('advisor_id') == Auth::id()">
                                {{ __('messages.assigned_projects') }}
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->isCommittee())
                            <x-nav-link :href="route('projects.index', ['status' => 'approved'])" :active="request()->routeIs('projects.*') && request('status') == 'approved'">
                                {{ __('messages.evaluate') }}
                            </x-nav-link>
                        @endif

                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')">
                            {{ __('messages.browse_projects') }}
                        </x-nav-link>

                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('messages.users') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                                {{ __('messages.categories') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.tags.index')" :active="request()->routeIs('admin.tags.*')">
                                {{ __('messages.tags') }}
                            </x-nav-link>
                            <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                                {{ __('messages.reports') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Language Switcher -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <svg class="w-5 h-5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                            <span>{{ app()->getLocale() == 'th' ? __('messages.thai') : __('messages.english') }}</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('locale.switch', 'en')">
                            🇬🇧 {{ __('messages.english') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('locale.switch', 'th')">
                            🇹🇭 {{ __('messages.thai') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('messages.profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('messages.logout') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">{{ __('messages.register') }}</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('messages.dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->isStudent())
                    <x-responsive-nav-link :href="route('projects.index', ['created_by' => Auth::id()])" :active="request()->routeIs('projects.*') && request('created_by') == Auth::id()">
                        {{ __('messages.my_projects') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isAdvisor())
                    <x-responsive-nav-link :href="route('projects.index', ['advisor_id' => Auth::id()])" :active="request()->routeIs('projects.*') && request('advisor_id') == Auth::id()">
                        {{ __('messages.assigned_projects') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isCommittee())
                    <x-responsive-nav-link :href="route('projects.index', ['status' => 'approved'])" :active="request()->routeIs('projects.*') && request('status') == 'approved'">
                        {{ __('messages.evaluate') }}
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')">
                    {{ __('messages.browse_projects') }}
                </x-responsive-nav-link>

                @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('messages.users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('messages.categories') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tags.index')" :active="request()->routeIs('admin.tags.*')">
                        {{ __('messages.tags') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        {{ __('messages.reports') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('messages.profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('messages.logout') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('messages.login') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('messages.register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth

        <!-- Language Switcher (Responsive) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-sm text-gray-500 mb-2">{{ __('messages.language') }}</div>
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('locale.switch', 'en')" :active="app()->getLocale() == 'en'">
                        🇬🇧 {{ __('messages.english') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('locale.switch', 'th')" :active="app()->getLocale() == 'th'">
                        🇹🇭 {{ __('messages.thai') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>
