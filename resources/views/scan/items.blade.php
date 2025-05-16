<x-app-layout>
    <x-slot name="header">
        <h2>Scan Items for {{ $shipment->tracking_number }}</h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        @foreach($shipment->items as $item)
            <div class="border p-4 mb-4">
                <div><strong>{{ $item->name }}</strong></div>
                <div>Required: {{ $item->required_quantity }}</div>
                <div>Scanned: {{ $item->scanned_quantity }}</div>
                <div>Status: {{ $item->completed ? '✅ Completed' : '❌ Pending' }}</div>

                @if(!$item->completed)
                    <form method="POST" action="{{ route('scan.item.update', $item->id) }}">
                        @csrf
                        <button type="submit" class="mt-2 px-3 py-1 bg-green-600 text-white rounded">Scan Item</button>
                    </form>
                @endif
            </div>
        @endforeach

        <a href="{{ route('scan.next') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded">Next Label</a>
    </div>
</x-app-layout>
