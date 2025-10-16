<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Tag Management</h2>
                        <p class="text-gray-600 mt-1">Manage project tags</p>
                    </div>

                    @if(session('success'))
                        <x-alert type="success" class="mb-4">
                            {{ session('success') }}
                        </x-alert>
                    @endif

                    @if(session('error'))
                        <x-alert type="error" class="mb-4">
                            {{ session('error') }}
                        </x-alert>
                    @endif

                    <!-- Add New Tag Form -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Add New Tag</h3>
                        <form action="{{ route('admin.tags.store') }}" method="POST" class="flex gap-3">
                            @csrf
                            <div class="flex-1">
                                <x-text-input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="block w-full" 
                                    placeholder="Enter tag name" 
                                    :value="old('name')" 
                                    required 
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <x-primary-button>
                                Add Tag
                            </x-primary-button>
                        </form>
                    </div>

                    <!-- Tags List -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tag Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projects</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tags as $tag)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $tag->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-badge type="info">
                                                {{ $tag->projects_count }} {{ Str::plural('project', $tag->projects_count) }}
                                            </x-badge>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline" onsubmit="return confirm('{{ $tag->projects_count > 0 ? 'This tag is used by ' . $tag->projects_count . ' project(s). Are you sure you want to delete it?' : 'Are you sure you want to delete this tag?' }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            No tags found. Add your first tag above.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $tags->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
