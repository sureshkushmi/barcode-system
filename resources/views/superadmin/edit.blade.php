<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <!-- Success Message -->
                @if(session('success'))
                    <p class="text-green-600">{{ session('success') }}</p>
                @endif

                <!-- Error Message -->
                @if($errors->any())
                    <div class="text-red-600 mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- User Edit Form -->
                <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input w-full rounded border-gray-300" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input w-full rounded border-gray-300" required>
                    </div>

                    <!-- Optional: Change password -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Password <small>(Leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-input w-full rounded border-gray-300">
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Role</label>
                        <select name="role" class="form-select w-full rounded border-gray-300">
                            <option value="users" {{ old('role', $user->role) == 'users' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Status</label>
                        <select name="status" class="form-select w-full rounded border-gray-300">
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update User</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
