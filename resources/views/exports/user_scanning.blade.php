<table>
    <thead>
        <tr>
            <th>Tracking #</th>
            <th>Total Qty</th>
            <th>Scanned Qty</th>
            <th>Scanned At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($shipments as $shipment)
        <tr>
            <td>{{ $shipment->tracking_number }}</td>
            <td>{{ $shipment->total_qty }}</td>
            <td>{{ $shipment->scanned_qty }}</td>
            <td>{{ $shipment->scanned_at }}</td>
            <td>{{ $shipment->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
