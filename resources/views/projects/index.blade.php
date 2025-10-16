<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('messages.projects') }}
            </h2>
            @auth
                @if(auth()->user()->role === 'student')
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('messages.create_project') }}
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('projects.index') }}" class="space-y-4">
                        <!-- Search Bar -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.search') }}</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="search" 
                                    value="{{ request('search') }}"
                                    placeholder="{{ __('messages.search_projects') }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Category Filter -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.category') }}</label>
                                <select 
                                    name="category_id" 
                                    id="category_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="">{{ __('messages.all_categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year Filter -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.year') }}</label>
                                <select 
                                    name="year" 
                                    id="year"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="">{{ __('messages.all_years') }}</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Semester Filter -->
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.semester') }}</label>
                                <select 
                                    name="semester" 
                                    id="semester"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="">{{ __('messages.all_semesters') }}</option>
                                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>{{ __('messages.semester') }} 1</option>
                                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>{{ __('messages.semester') }} 2</option>
                                    <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>{{ __('messages.semester') }} 3</option>
                                </select>
                            </div>

                            <!-- Advisor Filter -->
                            <div>
                                <label for="advisor_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.advisor') }}</label>
                                <select 
                                    name="advisor_id" 
                                    id="advisor_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="">{{ __('messages.all_advisors') }}</option>
                                    @foreach($advisors as $advisor)
                                        <option value="{{ $advisor->id }}" {{ request('advisor_id') == $advisor->id ? 'selected' : '' }}>
                                            {{ $advisor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter (only for authenticated users) -->
                            @auth
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.status') }}</label>
                                    <select 
                                        name="status" 
                                        id="status"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                        <option value="">{{ __('messages.all_statuses') }}</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('messages.draft') }}</option>
                                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>{{ __('messages.submitted') }}</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                    </select>
                                </div>
                            @endauth
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                {{ __('messages.filter') }}
                            </button>
                            <a 
                                href="{{ route('projects.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-4 text-sm text-gray-600">
                {{ __('messages.showing') }} {{ $projects->firstItem() ?? 0 }} {{ __('messages.of') }} {{ $projects->total() }} {{ __('messages.projects') }}
            </div>

            <!-- Projects Grid -->
            @if($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($projects as $project)
                        <x-project-card :project="$project" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $projects->links() }}
                </div>
            @else
                <x-empty-state 
                    title="{{ __('messages.no_projects_found') }}"
                    message="{{ __('messages.no_projects_found') }}"
                >
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        View All Projects
                    </a>
                </x-empty-state>
            @endif
        </div>
    </div>
</x-app-layout>
