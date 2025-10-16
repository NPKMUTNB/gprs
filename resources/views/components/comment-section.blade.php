{{--
    Comment Section Component
    
    Displays all comments for a project with the ability to add new comments.
    
    Props:
    - project: The project model instance with loaded comments relationship
    
    Features:
    - Displays all comments with user name and timestamp
    - Shows comment form for authenticated users
    - Allows admins to delete comments
    - Shows login prompt for guests
    
    Requirements: 8.1, 8.2, 8.3, 8.4, 8.5
--}}

@props(['project'])

<div>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Comments</h3>
    
    <!-- Existing Comments -->
    <div class="space-y-4 mb-6">
        @forelse($project->comments as $comment)
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-start space-x-3">
                        <!-- User Avatar Placeholder -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $comment->created_at->format('M d, Y \a\t g:i A') }}
                                <span class="mx-1">•</span>
                                {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="text-red-600 hover:text-red-800 text-xs font-medium transition-colors duration-150" 
                                    onclick="return confirm('Are you sure you want to delete this comment?')"
                                    title="Delete comment"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
                <div class="ml-13">
                    <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $comment->content }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500 italic">No comments yet. Be the first to comment!</p>
            </div>
        @endforelse
    </div>

    <!-- Add Comment Form -->
    @auth
        <div class="pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('comments.store', $project) }}" class="space-y-4">
                @csrf
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Add a comment
                    </label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="4" 
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('content') border-red-500 @enderror"
                        placeholder="Share your thoughts, feedback, or questions about this project..."
                        required
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
                </div>
                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Post Comment
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="pt-4 border-t border-gray-200">
            <div class="text-center py-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium underline">Login</a> 
                    or 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium underline">register</a> 
                    to add a comment
                </p>
            </div>
        </div>
    @endauth
</div>
