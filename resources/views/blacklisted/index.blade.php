<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            <span>{{ __('Blacklisted Workers') }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h1 class="text-2xl font-semibold mb-4">Blacklisted Workers</h1>
                    
                    <!-- Add New Button (Right Aligned) -->
                    <a href="{{ route('blacklisted.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ __('Add New') }}
                    </a>
                    
                    @if(session('success'))
                        <p class="text-green-600 font-semibold mb-4">{{ session('success') }}</p>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="border px-4 py-2">Name</th>
                                    <th class="border px-4 py-2">Email</th>
                                    <th class="border px-4 py-2">Phone</th>
                                    <th class="border px-4 py-2">Reason</th>
                                    <th class="border px-4 py-2">Proof</th>
                                    <th class="border px-4 py-2">Reported By</th>
                                    <th class="border px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workers as $worker)
                                    <tr class="border-b">
                                        <td class="border px-4 py-2">{{ $worker->name }}</td>
                                        <td class="border px-4 py-2">{{ $worker->email }}</td>
                                        <td class="border px-4 py-2">{{ $worker->phone }}</td>
                                        <td class="border px-4 py-2">{{ $worker->reason }}</td>
                                        <td class="border px-4 py-2">
                                            @if($worker->proof)
                                                <a href="{{ asset('storage/' . $worker->proof) }}" target="_blank" class="text-blue-500 hover:underline">View Proof</a>
                                            @else
                                                <span class="text-gray-500">No Proof</span>
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">{{ $worker->reported_by }}</td>
                                        <td class="border px-4 py-2 space-x-2">
                                            @if($worker->approved)
                                                <span class="text-green-500 font-semibold">Approved</span>
                                            @else
                                                <form method="POST" action="{{ route('admin.approve-blacklist', $worker->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Approve this worker?')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.reject-blacklist', $worker->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Reject this worker?')" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-gray-500">No blacklisted workers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (Optional) --}}
                    <div class="mt-4">
                        {{ $workers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
