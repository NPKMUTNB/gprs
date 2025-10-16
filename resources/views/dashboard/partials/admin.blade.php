{{-- Admin Dashboard --}}

{{-- System Statistics --}}
<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Overview</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['totalUsers'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Total Users</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['totalProjects'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Total Projects</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $stats['totalStudents'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Students</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600">{{ $stats['totalAdvisors'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Advisors</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600">{{ $stats['totalCommittee'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Committee</div>
            </div>
        </x-card>
    </div>
</div>

{{-- Project Statistics --}}
<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Status Distribution</h3>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-600">{{ $projectStats['draft'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Draft</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600">{{ $projectStats['submitted'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Submitted</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $projectStats['approved'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Approved</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600">{{ $projectStats['rejected'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Rejected</div>
            </div>
        </x-card>
        
        <x-card>
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $projectStats['completed'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Completed</div>
            </div>
        </x-card>
    </div>
</div>

{{-- Quick Actions --}}
<div class="mb-6">
    <x-card>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.index') }}">
                <x-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Manage Users
                </x-button>
            </a>
            
            <a href="{{ route('admin.categories.index') }}">
                <x-secondary-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Manage Categories
                </x-secondary-button>
            </a>
            
            <a href="{{ route('admin.tags.index') }}">
                <x-secondary-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Manage Tags
                </x-secondary-button>
            </a>
            
            <a href="{{ route('projects.index') }}">
                <x-secondary-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View All Projects
                </x-secondary-button>
            </a>
            
            <a href="{{ route('reports.index') }}">
                <x-secondary-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View Reports
                </x-secondary-button>
            </a>
        </div>
    </x-card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Projects --}}
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Projects</h3>
            <a href="{{ route('projects.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
        </div>
        
        @if($recentProjects->isEmpty())
            <x-empty-state>
                <p class="text-gray-600">No projects yet.</p>
            </x-empty-state>
        @else
            <div class="space-y-3">
                @foreach($recentProjects as $project)
                    <div class="border-l-4 border-blue-500 pl-3 py-2">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">
                                        {{ Str::limit($project->title_en, 50) }}
                                    </a>
                                </h4>
                                <div class="text-sm text-gray-600 mt-1">
                                    <span>By {{ $project->creator->name }}</span>
                                    @if($project->advisor)
                                        <span class="mx-1">•</span>
                                        <span>Advisor: {{ $project->advisor->name }}</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $project->category->name }} • {{ $project->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="ml-2">
                                <x-badge :type="$project->status">
                                    {{ ucfirst($project->status) }}
                                </x-badge>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>
    
    {{-- Recent Activities --}}
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
        </div>
        
        @if($recentActivities->isEmpty())
            <x-empty-state>
                <p class="text-gray-600">No recent activities.</p>
            </x-empty-state>
        @else
            <div class="space-y-3">
                @foreach($recentActivities as $activity)
                    <div class="border-l-4 border-gray-300 pl-3 py-2">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if(str_contains($activity->action, 'created'))
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                @elseif(str_contains($activity->action, 'updated'))
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                @elseif(str_contains($activity->action, 'deleted'))
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                @elseif(str_contains($activity->action, 'approved'))
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif(str_contains($activity->action, 'rejected'))
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $activity->user->name }}</span>
                                    <span class="text-gray-600">{{ $activity->action }}</span>
                                </p>
                                @if($activity->detail)
                                    <p class="text-xs text-gray-600 mt-1">{{ $activity->detail }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>
</div>
