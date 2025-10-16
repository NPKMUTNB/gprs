<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Project Details') }}
            </h2>
            <x-badge>{{ ucfirst($project->status) }}</x-badge>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Action Buttons -->
            @auth
                <div class="flex flex-wrap gap-3">
                    @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Project
                        </a>
                    @endcan

                    @if(auth()->id() === $project->created_by && $project->status === 'draft')
                        <form method="POST" action="{{ route('projects.submit', $project) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit for Review
                            </button>
                        </form>
                    @endif

                    @can('approve', $project)
                        @if($project->status === 'submitted')
                            <form method="POST" action="{{ route('projects.approve', $project) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Approve Project
                                </button>
                            </form>

                            <form method="POST" action="{{ route('projects.reject', $project) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to reject this project?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Reject Project
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('delete', $project)
                        <form method="POST" action="{{ route('projects.destroy', $project) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Project
                            </button>
                        </form>
                    @endcan

                    @if(Gate::allows('evaluate-project') && in_array($project->status, ['approved', 'completed']))
                        <a href="{{ route('projects.evaluations.create', $project) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Evaluate Project
                        </a>
                    @endif
                </div>
            @endauth

            <!-- Main Project Information -->
            <x-card>
                <div class="space-y-6">
                    <!-- Title Section -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $project->title_en }}</h3>
                        @if($project->title_th)
                            <p class="text-lg text-gray-600">{{ $project->title_th }}</p>
                        @endif
                    </div>

                    <!-- Meta Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 py-4 border-y border-gray-200">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Academic Year</span>
                            <p class="mt-1 text-base text-gray-900">{{ $project->year }} / Semester {{ $project->semester }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Category</span>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $project->category->name ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($project->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($project->status === 'submitted') bg-blue-100 text-blue-800
                                    @elseif($project->status === 'approved') bg-green-100 text-green-800
                                    @elseif($project->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($project->status === 'completed') bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Abstract -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Abstract</h4>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $project->abstract }}</p>
                    </div>

                    <!-- Tags -->
                    @if($project->tags->count() > 0)
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($project->tags as $tag)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>

            <!-- Team Members Section -->
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Team Members</h3>
                <x-team-member-form :project="$project" :students="$students" />
            </x-card>

            <!-- Advisor Section -->
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Advisor</h3>
                
                @if($project->advisor)
                    <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-10 h-10 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $project->advisor->name }}</p>
                            <p class="text-xs text-gray-500">{{ $project->advisor->email }}</p>
                            @if($project->advisor->department)
                                <p class="text-xs text-gray-500">{{ $project->advisor->department }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No advisor assigned</p>
                @endif
            </x-card>

            <!-- Project Files Section -->
            <x-card>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Project Files</h3>
                    @auth
                        @if(auth()->id() === $project->created_by || auth()->user()->role === 'admin')
                            <button onclick="document.getElementById('uploadFileModal').classList.remove('hidden')" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                + Upload File
                            </button>
                        @endif
                    @endauth
                </div>

                <x-file-list 
                    :files="$project->files" 
                    :project="$project" 
                    :can-delete="auth()->check() && (auth()->id() === $project->created_by || auth()->user()->role === 'admin')" 
                />
            </x-card>

            <!-- Evaluations Section (Hidden from guests) -->
            @auth
                @if($project->evaluations->count() > 0 || Gate::allows('evaluate-project'))
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Evaluations</h3>
                        
                        @if($project->evaluations->count() > 0)
                            <x-evaluation-display :evaluations="$project->evaluations" />
                        @else
                            <p class="text-sm text-gray-500 italic">No evaluations yet</p>
                        @endif
                    </x-card>
                @endif
            @endauth

            <!-- Comments Section -->
            <x-card>
                <x-comment-section :project="$project" />
            </x-card>

        </div>
    </div>

    <!-- File Upload Modal -->
    @auth
        @if(auth()->id() === $project->created_by || auth()->user()->role === 'admin')
            <div id="uploadFileModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload File</h3>
                        <form method="POST" action="{{ route('projects.files.store', $project) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="file_type" class="block text-sm font-medium text-gray-700 mb-2">File Type <span class="text-red-500">*</span></label>
                                <select name="file_type" id="file_type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('file_type') border-red-500 @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="proposal" {{ old('file_type') === 'proposal' ? 'selected' : '' }}>Proposal</option>
                                    <option value="report" {{ old('file_type') === 'report' ? 'selected' : '' }}>Report</option>
                                    <option value="presentation" {{ old('file_type') === 'presentation' ? 'selected' : '' }}>Presentation</option>
                                    <option value="code" {{ old('file_type') === 'code' ? 'selected' : '' }}>Source Code</option>
                                    <option value="other" {{ old('file_type') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('file_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">File <span class="text-red-500">*</span></label>
                                <input type="file" name="file" id="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('file') border-red-500 @enderror" required>
                                <p class="mt-1 text-xs text-gray-500">Maximum file size: 10MB</p>
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="document.getElementById('uploadFileModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if($errors->has('file') || $errors->has('file_type'))
                    document.getElementById('uploadFileModal').classList.remove('hidden');
                @endif
            });
        </script>
    @endif
</x-app-layout>
