<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            <span>{{ __('Edit Blacklisted Worker') }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h1 class="text-2xl font-semibold mb-4">Edit Blacklisted Worker</h1>

                    <!-- Success Message -->
                    @if(session('success'))
                        <p class="text-green-600 font-semibold mb-4">{{ session('success') }}</p>
                    @endif

                    <!-- Form to Edit Blacklisted Worker -->
                    <form action="{{ route('blacklisted.update', $worker->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="col-span-1">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $worker->name) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-span-1">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $worker->email) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-span-1">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $worker->phone) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                                @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Reason -->
                            <div class="col-span-1">
                                <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                <textarea id="reason" name="reason" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>{{ old('reason', $worker->reason) }}</textarea>
                                @error('reason') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <!-- Proof (Optional) -->
                            <div class="col-span-1">
                                <label for="proof" class="block text-sm font-medium text-gray-700">Proof</label>
                                <input type="file" id="proof" name="proof" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                @error('proof') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                                @if($worker->proof)
                                    <p class="mt-2 text-sm text-gray-500">Current Proof: <a href="{{ asset('storage/' . $worker->proof) }}" target="_blank" class="text-blue-500 hover:underline">View Proof</a></p>
                                @endif
                            </div>

                            <!-- Reported By -->
                            <div class="col-span-1">
                                <label for="reported_by" class="block text-sm font-medium text-gray-700">Reported By</label>
                                <input type="text" id="reported_by" name="reported_by" value="{{ old('reported_by', $worker->reported_by) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                                @error('reported_by') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Update Worker') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
