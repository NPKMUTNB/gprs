@props(['project'])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200']) }}>
    <div class="p-6">
        <!-- Status Badge -->
        <div class="flex justify-between items-start mb-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                @if($project->status === 'draft') bg-gray-100 text-gray-800
                @elseif($project->status === 'submitted') bg-blue-100 text-blue-800
                @elseif($project->status === 'approved') bg-green-100 text-green-800
                @elseif($project->status === 'rejected') bg-red-100 text-red-800
                @elseif($project->status === 'completed') bg-purple-100 text-purple-800
                @endif">
                {{ ucfirst($project->status) }}
            </span>
            <span class="text-xs text-gray-500">{{ $project->year }}/{{ $project->semester }}</span>
        </div>

        <!-- Title -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
            <a href="{{ route('projects.show', $project) }}" class="hover:text-blue-600">
                {{ $project->title_en }}
            </a>
        </h3>

        <!-- Thai Title -->
        @if($project->title_th)
            <p class="text-sm text-gray-600 mb-2 line-clamp-1">{{ $project->title_th }}</p>
        @endif

        <!-- Abstract -->
        <p class="text-sm text-gray-700 mb-4 line-clamp-3">
            {{ Str::limit($project->abstract, 150) }}
        </p>

        <!-- Category & Tags -->
        <div class="flex flex-wrap gap-2 mb-4">
            @if($project->category)
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                    {{ $project->category->name }}
                </span>
            @endif
            
            @foreach($project->tags->take(3) as $tag)
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                    {{ $tag->name }}
                </span>
            @endforeach
            
            @if($project->tags->count() > 3)
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                    +{{ $project->tags->count() - 3 }} more
                </span>
            @endif
        </div>

        <!-- Footer Info -->
        <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-200">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ $project->creator->name }}
            </div>
            
            @if($project->advisor)
                <div class="flex items-center">
                    <span class="mr-1">Advisor:</span>
                    {{ $project->advisor->name }}
                </div>
            @endif
        </div>

        <!-- Average Score (if evaluations exist) -->
        @if($project->evaluations->count() > 0)
            <div class="mt-3 pt-3 border-t border-gray-200">
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="font-medium text-gray-700">{{ number_format($project->averageScore(), 2) }}</span>
                    <span class="text-gray-500 ml-1">({{ $project->evaluations->count() }} {{ Str::plural('evaluation', $project->evaluations->count()) }})</span>
                </div>
            </div>
        @endif
    </div>
</div>
