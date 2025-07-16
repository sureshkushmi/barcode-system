@extends('layouts.admin')

@section('title', 'View Orders')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header d-flex justify-between items-center">
      <h3 class="card-title font-weight-bold">Orders</h3>
      <form method="GET" class="d-flex align-items-center">
        <input type="text" name="orderDateRange" id="order-date-range" class="form-control me-2" placeholder="Select date range" value="{{ request('orderDateRange') }}"
>
        <button type="submit" class="btn btn-primary">Filter</button>
      </form>
    </div>

    <div class="card-body">
      @if($orders->count())
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Status</th>
              <th>Order Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
             <tr>
  <td>{{ $order->shippingeasy_order_id }}</td>
  <td>{{ $order->customer_name }}</td>
  <td>{{ $order->status }}</td>
  <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i A') }}</td>
  <td>
    <button class="btn btn-info btn-sm view-order" data-id="{{ $order->id }}">View</button>
  </td>
</tr>

            @endforeach
          </tbody>
        </table>
        
        <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div id="order-details-content">
                  <!-- Dynamic Content will be loaded here -->
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="mt-3 d-flex justify-content-center">
          {!! $orders->links('vendor.pagination.bootstrap-5') !!}
        </div>
      @else
        <p>No orders found for selected date range.</p>
      @endif
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  $(document).on('click', '.view-order', function () {
    var orderId = $(this).data('id');
    const baseUrl = "{{ url('/') }}";
    $('#order-details-content').html('<div class="text-center">Loading...</div>');
    $('#orderDetailModal').modal('show');

    $.ajax({
      url: baseUrl+'/superadmin/orders/' + orderId,
      method: 'GET',
      success: function (response) {
        if (response.success) {
          const order = response.order;
          let html = `<p><strong>Customer:</strong> ${order.customer_name}</p>
                      <p><strong>Email:</strong> ${order.customer_email}</p>
                      <p><strong>Status:</strong> ${order.status}</p>
                      <p><strong>Order Date:</strong> ${new Date(order.order_date).toLocaleString()}</p>
                      <hr />
                      <h5>Items:</h5>
                      <ul>`;

          order.items.forEach(item => {
            const scanQty = item.scans.reduce((sum, s) => sum + s.quantity_scanned, 0);
            html += `<li>
                      <strong>${item.name}</strong><br>
                      Barcode: ${item.barcode}<br>
                      Quantity: ${item.quantity}<br>
                      Scanned Quantity: ${scanQty}<br>
                      Shipment: ${item.shipment ? item.shipment.tracking_number : 'N/A'}
                    </li><hr>`;
          });

          html += '</ul>';
          $('#order-details-content').html(html);
        } else {
          $('#order-details-content').html('<p class="text-danger">Failed to load order details.</p>');
        }
      },
      error: function () {
        $('#order-details-content').html('<p class="text-danger">An error occurred while loading.</p>');
      }
    });
  });
</script>
@endpush
