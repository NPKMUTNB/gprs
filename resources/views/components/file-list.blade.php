@props(['files', 'canDelete' => false])

@php
    // Group files by type
    $groupedFiles = $files->groupBy('file_type');
    $fileTypeLabels = [
        'proposal' => 'Proposals',
        'report' => 'Reports',
        'presentation' => 'Presentations',
        'code' => 'Source Code',
        'other' => 'Other Files'
    ];
    $fileTypeColors = [
        'proposal' => 'text-blue-500',
        'report' => 'text-green-500',
        'presentation' => 'text-purple-500',
        'code' => 'text-orange-500',
        'other' => 'text-gray-500'
    ];
@endphp

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @if($files->count() > 0)
        @foreach(['proposal', 'report', 'presentation', 'code', 'other'] as $type)
            @if($groupedFiles->has($type))
                <div class="space-y-2">
                    <!-- File Type Header -->
                    <div class="flex items-center space-x-2 pb-2 border-b border-gray-200">
                        <svg class="w-5 h-5 {{ $fileTypeColors[$type] }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        <h4 class="text-sm font-semibold text-gray-700">{{ $fileTypeLabels[$type] }}</h4>
                        <span class="text-xs text-gray-500">({{ $groupedFiles[$type]->count() }})</span>
                    </div>

                    <!-- Files in this type -->
                    <div class="space-y-2">
                        @foreach($groupedFiles[$type] as $file)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center flex-1 min-w-0">
                                    <!-- File Icon -->
                                    <div class="flex-shrink-0 mr-3">
                                        <svg class="w-8 h-8 {{ $fileTypeColors[$type] }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <!-- File Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $file->file_name }}
                                        </p>
                                        <div class="flex items-center text-xs text-gray-500 space-x-2">
                                            @if(method_exists($file, 'getFileSize'))
                                                <span>{{ $file->getFileSize() }}</span>
                                                <span>•</span>
                                            @endif
                                            <span>Uploaded {{ $file->created_at->format('M d, Y') }}</span>
                                            @if($file->created_at->diffInDays(now()) < 7)
                                                <span>•</span>
                                                <span class="text-green-600 font-medium">{{ $file->created_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Download Button -->
                                    <a href="{{ route('projects.files.download', [$file->project_id, $file->id]) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>

                                    <!-- Delete Button -->
                                    @if($canDelete)
                                        <form method="POST" action="{{ route('projects.files.destroy', [$file->project_id, $file->id]) }}" 
                                              onsubmit="return confirm('Are you sure you want to delete this file?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <p class="text-sm">No files uploaded yet</p>
        </div>
    @endif
</div>
