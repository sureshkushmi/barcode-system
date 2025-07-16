@extends('layouts.admin')

@section('title', 'User Scanned Shipments')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header">
      <h3>User Scanned Orders</h3>
    </div>

    <div class="card-body">
      @if($shipments->count())
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($shipments as $shipment)
            <tr>
              <td>{{ $shipment->tracking_number }}</td>
              <td>{{ Auth::user()->name }}</td> {{-- Or $shipment->customer_name if available --}}
              <td>{{ ucfirst($shipment->shipment_status) }}</td>
              <td>{{ \Carbon\Carbon::parse($shipment->scanned_at)->format('d M Y, h:i A') }}</td>
              <td>
                <button class="btn btn-info btn-sm view-shipment" data-id="{{ $shipment->shipment_id }}">
                  View
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      

        {!! $shipments->links('vendor.pagination.bootstrap-5') !!}
      @else
        <p>No scanned shipments found.</p>
      @endif
    </div>
  </div>

  <div class="modal fade" id="shipmentDetailModal" tabindex="-1" aria-labelledby="shipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="shipmentModalLabel">Shipment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="shipment-details-content">
          <!-- AJAX content here -->
        </div>
      </div>
    </div>
  </div>
</div>

</div>
@endsection
@push('scripts')
<script>
  $(document).on('click', '.view-shipment', function () {
    var shipmentId = $(this).data('id');

    // Use route helper and replace the placeholder
    var ajaxUrl = "{{ route('user.shipment.details', ['shipmentId' => '__SHIPMENT_ID__']) }}".replace('__SHIPMENT_ID__', shipmentId);

    $('#shipment-details-content').html('<div class="text-center">Loading...</div>');
    $('#shipmentDetailModal').modal('show');

    $.ajax({
      url: ajaxUrl,
      method: 'GET',
      success: function (response) {
        if (response.success) {
          const shipment = response.shipment;
          let html = `<p><strong>Tracking Number:</strong> ${shipment.tracking_number}</p>
                      <p><strong>Status:</strong> ${shipment.status}</p>
                      <p><strong>Created:</strong> ${new Date(shipment.created_at).toLocaleString()}</p>
                      <hr><h5>Scanned Items:</h5><ul>`;

          if (response.items.length > 0) {
            response.items.forEach(item => {
              html += `<li>
                        <strong>${item.item_name}</strong><br>
                        Barcode: ${item.barcode}<br>
                        Scanned Qty: ${item.quantity_scanned} / ${item.total_quantity}<br>
                        Scanned At: ${new Date(item.scanned_at).toLocaleString()}<br>
                        By: ${item.scanned_by}
                      </li><hr>`;
            });
          } else {
            html += '<li>No items scanned yet.</li>';
          }

          html += '</ul>';
          $('#shipment-details-content').html(html);
        } else {
          $('#shipment-details-content').html('<p class="text-danger">Failed to load shipment details.</p>');
        }
      },
      error: function () {
        $('#shipment-details-content').html('<p class="text-danger">An error occurred.</p>');
      }
    });
  });
</script>

@endpush
