<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
                        <p class="text-gray-600 mt-1">Update user information</p>
                    </div>

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <x-form-group>
                                <x-input-label for="name" value="Name" />
                                <x-text-input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    :value="old('name', $user->name)" 
                                    required 
                                    autofocus 
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </x-form-group>

                            <x-form-group>
                                <x-input-label for="email" value="Email" />
                                <x-text-input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    class="mt-1 block w-full" 
                                    :value="old('email', $user->email)" 
                                    required 
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </x-form-group>

                            <x-form-group>
                                <x-input-label for="role" value="Role" />
                                <x-select 
                                    id="role" 
                                    name="role" 
                                    class="mt-1 block w-full" 
                                    required
                                >
                                    <option value="">Select Role</option>
                                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="advisor" {{ old('role', $user->role) === 'advisor' ? 'selected' : '' }}>Advisor</option>
                                    <option value="committee" {{ old('role', $user->role) === 'committee' ? 'selected' : '' }}>Committee</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </x-select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </x-form-group>

                            <x-form-group>
                                <x-input-label for="department" value="Department (Optional)" />
                                <x-text-input 
                                    id="department" 
                                    name="department" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    :value="old('department', $user->department)" 
                                />
                                <x-input-error :messages="$errors->get('department')" class="mt-2" />
                            </x-form-group>

                            <div class="border-t pt-6">
                                <p class="text-sm text-gray-600 mb-4">Leave password fields empty to keep current password</p>
                                
                                <x-form-group>
                                    <x-input-label for="password" value="New Password (Optional)" />
                                    <x-text-input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        class="mt-1 block w-full" 
                                    />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                                </x-form-group>

                                <x-form-group>
                                    <x-input-label for="password_confirmation" value="Confirm New Password" />
                                    <x-text-input 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        type="password" 
                                        class="mt-1 block w-full" 
                                    />
                                </x-form-group>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                            <x-primary-button>
                                Update User
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
