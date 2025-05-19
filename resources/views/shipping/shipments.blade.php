<x-app-layout>
    <x-slot name="header">Shipments</x-slot>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tracking Number</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
                <tr>
                    <td>{{ $shipment->id }}</td>
                    <td>{{ $shipment->tracking_number }}</td>
                    <td>{{ $shipment->status }}</td>
                    <td>{{ $shipment->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $shipments->links() }}
</x-app-layout>
