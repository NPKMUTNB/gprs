<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('projects.store') }}" class="space-y-6">
                        @csrf

                        <!-- Title (Thai) -->
                        <x-form-group label="Title (Thai)" name="title_th" required>
                            <x-text-input 
                                id="title_th" 
                                name="title_th" 
                                type="text" 
                                class="block w-full" 
                                :value="old('title_th')" 
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
                                :value="old('title_en')" 
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
                            >{{ old('abstract') }}</x-textarea>
                            <p class="mt-1 text-sm text-gray-500">Describe the objectives, methodology, and expected outcomes of your project.</p>
                        </x-form-group>

                        <!-- Year and Semester -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Year -->
                            <x-form-group label="Year" name="year" required>
                                <x-select id="year" name="year" class="block w-full" required>
                                    <option value="">Select Year</option>
                                    @for($year = date('Y') + 1; $year >= 2000; $year--)
                                        <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </x-select>
                            </x-form-group>

                            <!-- Semester -->
                            <x-form-group label="Semester" name="semester" required>
                                <x-select id="semester" name="semester" class="block w-full" required>
                                    <option value="">Select Semester</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                    <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>Semester 3 (Summer)</option>
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
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                                @if($categories->isEmpty())
                                    <p class="mt-1 text-sm text-red-600">No categories available. Please contact an administrator.</p>
                                @endif
                            </x-form-group>

                            <!-- Advisor -->
                            <x-form-group label="Advisor" name="advisor_id" required>
                                <x-select id="advisor_id" name="advisor_id" class="block w-full" required>
                                    <option value="">Select Advisor</option>
                                    @foreach($advisors as $advisor)
                                        <option value="{{ $advisor->id }}" {{ old('advisor_id') == $advisor->id ? 'selected' : '' }}>
                                            {{ $advisor->name }}
                                            @if($advisor->department)
                                                ({{ $advisor->department }})
                                            @endif
                                        </option>
                                    @endforeach
                                </x-select>
                                @if($advisors->isEmpty())
                                    <p class="mt-1 text-sm text-red-600">No advisors available. Please contact an administrator.</p>
                                @endif
                            </x-form-group>
                        </div>

                        <!-- Tags -->
                        <x-form-group label="Tags" name="tags">
                            <div class="space-y-2">
                                <p class="text-sm text-gray-500">Select relevant tags for your project (optional)</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-4">
                                    @forelse($tags as $tag)
                                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input 
                                                type="checkbox" 
                                                name="tags[]" 
                                                value="{{ $tag->id }}"
                                                {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
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

                        <!-- Information Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Project Creation Information</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Your project will be saved as a <strong>draft</strong> initially</li>
                                            <li>You can edit and add files to draft projects</li>
                                            <li>Submit your project for review when ready</li>
                                            <li>Once submitted, your advisor will review and approve/reject it</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                            <a 
                                href="{{ route('projects.index') }}" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Cancel
                            </a>
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Create Project
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
