{{-- Advisor Dashboard --}}

{{-- Quick Stats --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $totalProjects }}</div>
            <div class="text-sm text-gray-600 mt-1">Total Assigned</div>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-orange-600">{{ $statusCounts['submitted'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Awaiting Review</div>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-green-600">{{ $statusCounts['approved'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Approved</div>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-purple-600">{{ $statusCounts['completed'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Completed</div>
        </div>
    </x-card>
</div>

{{-- Quick Actions --}}
<div class="mb-6">
    <x-card>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('projects.index', ['advisor_id' => $user->id]) }}">
                <x-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View My Projects
                </x-button>
            </a>
            
            <a href="{{ route('projects.index') }}">
                <x-secondary-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Browse All Projects
                </x-button>
            </a>
            
            <a href="{{ route('reports.index') }}">
                <x-secondary-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View Reports
                </x-button>
            </a>
        </div>
    </x-card>
</div>

{{-- Projects Requiring Action --}}
@if($projectsRequiringAction->isNotEmpty())
    <div class="mb-6">
        <x-card>
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Projects Requiring Action ({{ $projectsRequiringAction->count() }})</h3>
            </div>
            
            <div class="space-y-4">
                @foreach($projectsRequiringAction as $project)
                    <div class="border border-orange-200 bg-orange-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">
                                        {{ $project->title_en }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($project->abstract, 120) }}</p>
                                
                                <div class="flex flex-wrap gap-2 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $project->creator->name }}
                                    </span>
                                    
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $project->category->name }}
                                    </span>
                                    
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $project->year }}/{{ $project->semester }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="ml-4">
                                <x-badge type="submitted">Submitted</x-badge>
                            </div>
                        </div>
                        
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('projects.show', $project) }}">
                                <x-button>Review Project</x-button>
                            </a>
                            
                            <form action="{{ route('projects.approve', $project) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700" onclick="return confirm('Are you sure you want to approve this project?')">
                                    Approve
                                </button>
                            </form>
                            
                            <form action="{{ route('projects.reject', $project) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700" onclick="return confirm('Are you sure you want to reject this project?')">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
@endif

{{-- All Assigned Projects --}}
<x-card>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">All Assigned Projects</h3>
        <a href="{{ route('projects.index', ['advisor_id' => $user->id]) }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
    </div>
    
    @if($projects->isEmpty())
        <x-empty-state>
            <p class="text-gray-600">No projects assigned to you yet.</p>
        </x-empty-state>
    @else
        <div class="space-y-4">
            @foreach($projects->take(5) as $project)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">
                                    {{ $project->title_en }}
                                </a>
                            </h4>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($project->abstract, 120) }}</p>
                            
                            <div class="flex flex-wrap gap-2 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $project->creator->name }}
                                </span>
                                
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $project->category->name }}
                                </span>
                                
                                @if($project->evaluations->isNotEmpty())
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        Avg: {{ number_format($project->averageScore(), 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="ml-4">
                            <x-badge :type="$project->status">
                                {{ ucfirst($project->status) }}
                            </x-badge>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('projects.show', $project) }}" class="text-sm text-blue-600 hover:text-blue-800">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-card>
