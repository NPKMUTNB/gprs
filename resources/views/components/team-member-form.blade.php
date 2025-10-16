@props(['project', 'students' => null])

<div class="space-y-4">
    <!-- Current Team Members -->
    <div class="space-y-3">
        <!-- Creator -->
        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $project->creator->name }}</p>
                    <p class="text-xs text-gray-500">{{ $project->creator->email }}</p>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                Project Creator
            </span>
        </div>

        <!-- Other Team Members -->
        @foreach($project->members as $member)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $member->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $member->user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        {{ ucfirst($member->role_in_team) }}
                    </span>
                    @auth
                        @if(auth()->id() === $project->created_by || auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="text-red-600 hover:text-red-800 text-xs font-medium"
                                    onclick="return confirm('Are you sure you want to remove this team member?')"
                                    title="Remove member"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach

        @if($project->members->count() === 0)
            <p class="text-sm text-gray-500 italic">No additional team members</p>
        @endif
    </div>

    <!-- Add Team Member Form -->
    @auth
        @if(auth()->id() === $project->created_by && in_array($project->status, ['draft', 'rejected']))
            <div class="pt-4 border-t border-gray-200">
                <button 
                    type="button"
                    onclick="document.getElementById('addMemberModal').classList.remove('hidden')"
                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium"
                >
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Team Member
                </button>
            </div>

            <!-- Add Member Modal -->
            <div id="addMemberModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Add Team Member</h3>
                            <button 
                                type="button"
                                onclick="document.getElementById('addMemberModal').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <form method="POST" action="{{ route('projects.members.store', $project) }}">
                            @csrf
                            
                            <!-- User Selection -->
                            <div class="mb-4">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Student <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="user_id" 
                                    id="user_id" 
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('user_id') border-red-500 @enderror" 
                                    required
                                >
                                    <option value="">Select a student</option>
                                    @if($students)
                                        @foreach($students as $student)
                                            @if($student->id !== $project->created_by && !$project->members->contains('user_id', $student->id))
                                                <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} ({{ $student->email }})
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role Selection -->
                            <div class="mb-4">
                                <label for="role_in_team" class="block text-sm font-medium text-gray-700 mb-2">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="role_in_team" 
                                    id="role_in_team" 
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('role_in_team') border-red-500 @enderror" 
                                    required
                                >
                                    <option value="">Select role</option>
                                    <option value="leader" {{ old('role_in_team') === 'leader' ? 'selected' : '' }}>Leader</option>
                                    <option value="member" {{ old('role_in_team') === 'member' ? 'selected' : '' }}>Member</option>
                                </select>
                                @error('role_in_team')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3">
                                <button 
                                    type="button" 
                                    onclick="document.getElementById('addMemberModal').classList.add('hidden')" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm font-medium"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium"
                                >
                                    Add Member
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->has('user_id') || $errors->has('role_in_team'))
                document.getElementById('addMemberModal').classList.remove('hidden');
            @endif
        });
    </script>
@endif
