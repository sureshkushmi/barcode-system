<x-app-layout>
    <x-slot name="header">
        <h2>Scan Shipping Label</h2>
    </x-slot>

    <div class="p-6 max-w-md mx-auto">
        @if(session('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('scan.label') }}">
            @csrf
            <label for="tracking_number">Tracking Number</label>
            <input type="text" name="tracking_number" class="w-full border p-2 mb-4" required>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Fetch Items</button>
        </form>
    </div>
</x-app-layout>
