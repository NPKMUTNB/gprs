<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Project
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Evaluate Project</h1>
                <p class="mt-2 text-gray-600">{{ $project->title_en }}</p>
            </div>

            <!-- Project Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Category</p>
                        <p class="font-semibold text-gray-900">{{ $project->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created By</p>
                        <p class="font-semibold text-gray-900">{{ $project->creator->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Advisor</p>
                        <p class="font-semibold text-gray-900">{{ $project->advisor->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Year / Semester</p>
                        <p class="font-semibold text-gray-900">{{ $project->year }} / {{ $project->semester }}</p>
                    </div>
                </div>
            </div>

            <!-- Evaluation Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ route('projects.evaluations.store', $project) }}" id="evaluationForm">
                    @csrf

                    <!-- Total Score Preview -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200">
                        <div class="text-center">
                            <p class="text-sm font-semibold text-blue-900 mb-2">Calculated Total Score</p>
                            <div class="flex items-center justify-center">
                                <svg class="w-8 h-8 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span id="totalScore" class="text-5xl font-bold text-blue-900">0.00</span>
                                <span class="text-xl text-blue-700 ml-2">/ 100</span>
                            </div>
                            <p class="text-xs text-blue-700 mt-2">Average of all four scores</p>
                        </div>
                    </div>

                    <!-- Score Input Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Technical Score -->
                        <div>
                            <label for="technical_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Technical Score
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="technical_score" 
                                    id="technical_score"
                                    min="0" 
                                    max="100" 
                                    step="0.01"
                                    value="{{ old('technical_score') }}"
                                    class="score-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('technical_score') border-red-500 @enderror"
                                    placeholder="0-100"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 text-sm">/ 100</span>
                                </div>
                            </div>
                            @error('technical_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Evaluate technical implementation and code quality</p>
                        </div>

                        <!-- Design Score -->
                        <div>
                            <label for="design_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Design Score
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="design_score" 
                                    id="design_score"
                                    min="0" 
                                    max="100" 
                                    step="0.01"
                                    value="{{ old('design_score') }}"
                                    class="score-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('design_score') border-red-500 @enderror"
                                    placeholder="0-100"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 text-sm">/ 100</span>
                                </div>
                            </div>
                            @error('design_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Evaluate UI/UX design and architecture</p>
                        </div>

                        <!-- Documentation Score -->
                        <div>
                            <label for="documentation_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Documentation Score
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="documentation_score" 
                                    id="documentation_score"
                                    min="0" 
                                    max="100" 
                                    step="0.01"
                                    value="{{ old('documentation_score') }}"
                                    class="score-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('documentation_score') border-red-500 @enderror"
                                    placeholder="0-100"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 text-sm">/ 100</span>
                                </div>
                            </div>
                            @error('documentation_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Evaluate documentation quality and completeness</p>
                        </div>

                        <!-- Presentation Score -->
                        <div>
                            <label for="presentation_score" class="block text-sm font-semibold text-gray-700 mb-2">
                                Presentation Score
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="presentation_score" 
                                    id="presentation_score"
                                    min="0" 
                                    max="100" 
                                    step="0.01"
                                    value="{{ old('presentation_score') }}"
                                    class="score-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('presentation_score') border-red-500 @enderror"
                                    placeholder="0-100"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 text-sm">/ 100</span>
                                </div>
                            </div>
                            @error('presentation_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Evaluate presentation skills and delivery</p>
                        </div>
                    </div>

                    <!-- Comment Textarea -->
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">
                            Comments
                            <span class="text-gray-500 font-normal">(Optional)</span>
                        </label>
                        <textarea 
                            name="comment" 
                            id="comment"
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('comment') border-red-500 @enderror"
                            placeholder="Provide detailed feedback about the project..."
                        >{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Share your thoughts, suggestions, and feedback</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('projects.show', $project) }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Total Score Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scoreInputs = document.querySelectorAll('.score-input');
            const totalScoreDisplay = document.getElementById('totalScore');

            function calculateTotalScore() {
                const technical = parseFloat(document.getElementById('technical_score').value) || 0;
                const design = parseFloat(document.getElementById('design_score').value) || 0;
                const documentation = parseFloat(document.getElementById('documentation_score').value) || 0;
                const presentation = parseFloat(document.getElementById('presentation_score').value) || 0;

                const total = (technical + design + documentation + presentation) / 4;
                totalScoreDisplay.textContent = total.toFixed(2);
            }

            // Add event listeners to all score inputs
            scoreInputs.forEach(input => {
                input.addEventListener('input', calculateTotalScore);
                input.addEventListener('change', calculateTotalScore);
            });

            // Calculate initial total if there are old values
            calculateTotalScore();
        });
    </script>
</x-app-layout>
