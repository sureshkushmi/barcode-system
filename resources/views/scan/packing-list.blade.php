<x-app-layout>
    <x-slot name="header">
        <h2>Packing List for Shipment: {{ $shipment->tracking_number }}</h2>
    </x-slot>

    <div class="p-6">
        <table class="w-full border">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Barcode</th>
                    <th>Required</th>
                    <th>Scanned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr class="border-t">
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->barcode }}</td>
                        <td>{{ $item->required_quantity }}</td>
                        <td>{{ $item->completed }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
