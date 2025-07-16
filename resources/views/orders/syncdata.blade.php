@extends('layouts.admin')

@section('title', 'Sync Orders')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header d-flex justify-between items-center">
      <h3 class="card-title font-weight-bold">Sync Orders From ShippingEasy</h3>
    </div>

    <div class="card-body">

      @if(isset($success) && $success)
        <div class="alert alert-success">
          ✅ Sync completed successfully.<br>
          <strong>{{ $orderCount }}</strong> orders and <strong>{{ $itemCount }}</strong> items were synced.
        </div>
      @endif
      @if(isset($skippedOrders) && count($skippedOrders))
  <div class="mt-4">
    <h5>⚠️ Skipped Orders (Missing Tracking Numbers):</h5>
    <table class="table table-bordered">
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
        @foreach($skippedOrders as $order)
        <tr>
          <td>{{ $order['shippingeasy_order_id'] }}</td>
          <td>{{ $order['customer_name'] }}</td>
          <td>{{ $order['status'] }}</td>
          <td>{{ \Carbon\Carbon::parse($order['order_date'])->format('d M Y, h:i A') }}</td>
          <td>
            <a href="{{ url('superadmin/orders/' . $order['order_id']) }}" class="btn btn-info btn-sm" target="_blank">View</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

      @if(isset($error))
        <div class="alert alert-danger">
          ❌ Sync failed: {{ $error }}
        </div>
      @endif

      <!--<p>This section will show the result of syncing ShippingEasy data.</p> -->

    </div>
  </div>
</div>
@endsection
