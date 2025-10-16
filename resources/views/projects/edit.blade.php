<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Project') }}
            </h2>
            <x-badge>{{ ucfirst($project->status) }}</x-badge>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title (Thai) -->
                        <x-form-group label="Title (Thai)" name="title_th" required>
                            <x-text-input 
                                id="title_th" 
                                name="title_th" 
                                type="text" 
                                class="block w-full" 
                                :value="old('title_th', $project->title_th)" 
                                required 
                                autofocus
                                placeholder="ชื่อโครงงาน (ภาษาไทย)"
                            />
                        </x-form-group>

                        <!-- Title (English) -->
                        <x-form-group label="Title (English)" name="title_en" required>
                            <x-text-input 
                                id="title_en" 
                                name="title_en" 
                                type="text" 
                                class="block w-full" 
                                :value="old('title_en', $project->title_en)" 
                                required
                                placeholder="Project Title (English)"
                            />
                        </x-form-group>

                        <!-- Abstract -->
                        <x-form-group label="Abstract" name="abstract" required>
                            <x-textarea 
                                id="abstract" 
                                name="abstract" 
                                rows="6" 
                                class="block w-full" 
                                required
                                placeholder="Provide a detailed description of your project..."
                            >{{ old('abstract', $project->abstract) }}</x-textarea>
                            <p class="mt-1 text-sm text-gray-500">Describe the objectives, methodology, and expected outcomes of your project.</p>
                        </x-form-group>

                        <!-- Year and Semester -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Year -->
                            <x-form-group label="Year" name="year" required>
                                <x-select id="year" name="year" class="block w-full" required>
                                    <option value="">Select Year</option>
                                    @for($year = date('Y') + 1; $year >= 2000; $year--)
                                        <option value="{{ $year }}" {{ old('year', $project->year) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </x-select>
                            </x-form-group>

                            <!-- Semester -->
                            <x-form-group label="Semester" name="semester" required>
                                <x-select id="semester" name="semester" class="block w-full" required>
                                    <option value="">Select Semester</option>
                                    <option value="1" {{ old('semester', $project->semester) == '1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ old('semester', $project->semester) == '2' ? 'selected' : '' }}>Semester 2</option>
                                    <option value="3" {{ old('semester', $project->semester) == '3' ? 'selected' : '' }}>Semester 3 (Summer)</option>
                                </x-select>
                            </x-form-group>
                        </div>

                        <!-- Category and Advisor -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category -->
                            <x-form-group label="Category" name="category_id" required>
                                <x-select id="category_id" name="category_id" class="block w-full" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </x-form-group>

                            <!-- Advisor -->
                            <x-form-group label="Advisor" name="advisor_id" required>
                                <x-select id="advisor_id" name="advisor_id" class="block w-full" required>
                                    <option value="">Select Advisor</option>
                                    @foreach($advisors as $advisor)
                                        <option value="{{ $advisor->id }}" {{ old('advisor_id', $project->advisor_id) == $advisor->id ? 'selected' : '' }}>
                                            {{ $advisor->name }}
                                            @if($advisor->department)
                                                ({{ $advisor->department }})
                                            @endif
                                        </option>
                                    @endforeach
                                </x-select>
                            </x-form-group>
                        </div>

                        <!-- Tags -->
                        <x-form-group label="Tags" name="tags">
                            <div class="space-y-2">
                                <p class="text-sm text-gray-500">Select relevant tags for your project (optional)</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-4">
                                    @php
                                        $selectedTags = old('tags', $project->tags->pluck('id')->toArray());
                                    @endphp
                                    @forelse($tags as $tag)
                                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input 
                                                type="checkbox" 
                                                name="tags[]" 
                                                value="{{ $tag->id }}"
                                                {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                            >
                                            <span class="text-sm text-gray-700">{{ $tag->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500 col-span-full">No tags available.</p>
                                    @endforelse
                                </div>
                            </div>
                        </x-form-group>

                        <!-- Team Members -->
                        @if(in_array($project->status, ['draft', 'rejected']))
                            <div class="border border-gray-300 rounded-lg p-4">
                                <h4 class="text-base font-semibold text-gray-900 mb-3">Team Members</h4>
                                <x-team-member-form :project="$project" :students="$students" />
                            </div>
                        @endif

                        <!-- Status Information -->
                        @if($project->status !== 'draft')
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Project Status: {{ ucfirst($project->status) }}</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            @if($project->status === 'submitted')
                                                <p>This project is currently under review by your advisor.</p>
                                            @elseif($project->status === 'approved')
                                                <p>This project has been approved. Changes may require re-approval.</p>
                                            @elseif($project->status === 'rejected')
                                                <p>This project was rejected. You can make changes and resubmit.</p>
                                            @elseif($project->status === 'completed')
                                                <p>This project is marked as completed.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                            <a 
                                href="{{ route('projects.show', $project) }}" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Cancel
                            </a>
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Project
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
