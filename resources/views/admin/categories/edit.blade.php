<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Edit Category</h2>
                        <p class="text-gray-600 mt-1">Update category information</p>
                    </div>

                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <x-form-group>
                                <x-input-label for="name" value="Category Name" />
                                <x-text-input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    :value="old('name', $category->name)" 
                                    required 
                                    autofocus 
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </x-form-group>

                            <x-form-group>
                                <x-input-label for="description" value="Description (Optional)" />
                                <x-textarea 
                                    id="description" 
                                    name="description" 
                                    rows="4" 
                                    class="mt-1 block w-full"
                                >{{ old('description', $category->description) }}</x-textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </x-form-group>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                            <x-primary-button>
                                Update Category
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
