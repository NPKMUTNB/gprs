@props(['evaluations'])

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @forelse($evaluations as $evaluation)
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <!-- Evaluator Info -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-semibold text-sm">
                            {{ strtoupper(substr($evaluation->evaluator->name, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $evaluation->evaluator->name }}</p>
                        <p class="text-xs text-gray-500">{{ $evaluation->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <!-- Total Score Badge -->
                <div class="text-right">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100">
                        <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-lg font-bold text-blue-900">{{ number_format($evaluation->total_score, 2) }}</span>
                        <span class="text-xs text-blue-700 ml-1">/ 100</span>
                    </div>
                </div>
            </div>

            <!-- Score Breakdown -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <!-- Technical Score -->
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Technical</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $evaluation->technical_score }}</p>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $evaluation->technical_score }}%"></div>
                    </div>
                </div>

                <!-- Design Score -->
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Design</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $evaluation->design_score }}</p>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $evaluation->design_score }}%"></div>
                    </div>
                </div>

                <!-- Documentation Score -->
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Documentation</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $evaluation->documentation_score }}</p>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $evaluation->documentation_score }}%"></div>
                    </div>
                </div>

                <!-- Presentation Score -->
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600 mb-1">Presentation</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $evaluation->presentation_score }}</p>
                    <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-orange-600 h-1.5 rounded-full" style="width: {{ $evaluation->presentation_score }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Comment -->
            @if($evaluation->comment)
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs font-semibold text-gray-700 mb-1">Comments:</p>
                    <p class="text-sm text-gray-700">{{ $evaluation->comment }}</p>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <p class="text-sm">No evaluations yet</p>
        </div>
    @endforelse

    <!-- Average Score Summary (if multiple evaluations) -->
    @if($evaluations->count() > 1)
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-1">Average Score</p>
                    <p class="text-xs text-blue-700">Based on {{ $evaluations->count() }} evaluations</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-blue-900">
                        {{ number_format($evaluations->avg('total_score'), 2) }}
                    </p>
                    <p class="text-xs text-blue-700">out of 100</p>
                </div>
            </div>
            
            <!-- Average Breakdown -->
            <div class="grid grid-cols-4 gap-3 mt-4">
                <div class="text-center">
                    <p class="text-xs text-blue-700 mb-1">Technical</p>
                    <p class="text-lg font-bold text-blue-900">{{ number_format($evaluations->avg('technical_score'), 1) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-blue-700 mb-1">Design</p>
                    <p class="text-lg font-bold text-blue-900">{{ number_format($evaluations->avg('design_score'), 1) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-blue-700 mb-1">Documentation</p>
                    <p class="text-lg font-bold text-blue-900">{{ number_format($evaluations->avg('documentation_score'), 1) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-blue-700 mb-1">Presentation</p>
                    <p class="text-lg font-bold text-blue-900">{{ number_format($evaluations->avg('presentation_score'), 1) }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
