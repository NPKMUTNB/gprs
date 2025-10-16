{{-- Committee Dashboard --}}

{{-- Quick Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $totalAvailable }}</div>
            <div class="text-sm text-gray-600 mt-1">Available Projects</div>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-orange-600">{{ $totalPending }}</div>
            <div class="text-sm text-gray-600 mt-1">Pending Evaluation</div>
        </div>
    </x-card>
    
    <x-card>
        <div class="text-center">
            <div class="text-3xl font-bold text-green-600">{{ $totalEvaluated }}</div>
            <div class="text-sm text-gray-600 mt-1">Evaluated</div>
        </div>
    </x-card>
</div>

{{-- Quick Actions --}}
<div class="mb-6">
    <x-card>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('projects.index', ['status' => 'approved']) }}">
                <x-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    View Projects to Evaluate
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

{{-- Projects Pending Evaluation --}}
@if($projectsToEvaluate->isNotEmpty())
    <div class="mb-6">
        <x-card>
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Projects Pending Your Evaluation ({{ $projectsToEvaluate->count() }})</h3>
            </div>
            
            <div class="space-y-4">
                @foreach($projectsToEvaluate->take(5) as $project)
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
                                    
                                    @if($project->advisor)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            Advisor: {{ $project->advisor->name }}
                                        </span>
                                    @endif
                                    
                                    @if($project->evaluations->isNotEmpty())
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                            {{ $project->evaluations->count() }} evaluation(s)
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
                        
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('projects.show', $project) }}">
                                <x-secondary-button>View Project</x-secondary-button>
                            </a>
                            
                            <a href="{{ route('projects.evaluations.create', $project) }}">
                                <x-button>Evaluate Now</x-button>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($projectsToEvaluate->count() > 5)
                <div class="mt-4 text-center">
                    <a href="{{ route('projects.index', ['status' => 'approved']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                        View all {{ $projectsToEvaluate->count() }} projects pending evaluation
                    </a>
                </div>
            @endif
        </x-card>
    </div>
@endif

{{-- Already Evaluated Projects --}}
@if($evaluatedProjects->isNotEmpty())
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Your Evaluated Projects</h3>
            <span class="text-sm text-gray-600">{{ $evaluatedProjects->count() }} projects</span>
        </div>
        
        <div class="space-y-4">
            @foreach($evaluatedProjects->take(5) as $project)
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
                                
                                @php
                                    $userEvaluation = $project->evaluations->where('evaluator_id', $user->id)->first();
                                @endphp
                                
                                @if($userEvaluation)
                                    <span class="flex items-center text-green-600 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Your Score: {{ number_format($userEvaluation->total_score, 2) }}
                                    </span>
                                @endif
                                
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
    </x-card>
@else
    <x-card>
        <x-empty-state>
            <p class="text-gray-600">You haven't evaluated any projects yet.</p>
        </x-empty-state>
    </x-card>
@endif
