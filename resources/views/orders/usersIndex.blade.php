@extends('layouts.admin')

@section('title', 'View Orders')

@section('content')
<div class="container-fluid py-4">
  <div class="card">
    <div class="card-header d-flex justify-between items-center">
      <h3 class="card-title font-weight-bold">Orders</h3>
      <form method="GET" class="d-flex align-items-center">
        <input type="text" name="orderDateRange" id="order-date-range" class="form-control me-2" placeholder="Select date range" value="{{ old('orderDateRange', request('orderDateRange')) }}"
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
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="mt-3 d-flex justify-content-center">
          {!! $orders->links('vendor.pagination.bootstrap-5') !!}
        </div>
      @else
        <p>No orders found.</p>
      @endif
    </div>
  </div>
</div>
@endsection
