<table>
  <thead>
    <tr>
      <th>Tracking Number</th>
      <th>Total Qty</th>
      <th>Scanned Qty</th>
      <th>Status</th>
      <th>Scanned At</th>
    </tr>
  </thead>
  <tbody>
    @foreach($shipments as $shipment)
      <tr>
        <td>{{ $shipment->tracking_number }}</td>
        <td>{{ $shipment->total_qty }}</td>
        <td>{{ $shipment->scanned_qty }}</td>
        <td>{{ $shipment->status }}</td>
        <td>{{ $shipment->scanned_at }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
