@extends('layouts.admin')

@section('title', 'User Scan Report')


@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Item Scanning Report by User</h3>

      <form method="GET" class="d-flex align-items-center">
        <select name="user_id" class="form-select me-2" onchange="this.form.submit()">
          <option value="">All Users</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
              {{ $user->name }}
            </option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
      </form>
    </div>

    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead class="table-light">
          <tr>
            <th>Order ID / Number</th>
            <th>Customer Name</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Tracking #</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $item)
          <tr>
            <td>{{ $item->shippingeasy_order_id }}</td>
            <td>{{ $item->customer_name }}</td>
            <td>{{ $item->order_date }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td>{{ $item->tracking_number ?? '-' }}</td>
            <td>
              <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $item->item_id }}">
                View
              </button>

              <!-- Modal -->
              <div class="modal fade" id="detailsModal{{ $item->item_id }}" tabindex="-1" aria-labelledby="detailsLabel{{ $item->item_id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                      <h5 class="modal-title" id="detailsLabel{{ $item->item_id }}">
                        Scan Details for - {{ $item->item_name }}
                      </h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      <p><strong>Barcode:</strong> {{ $item->barcode }}</p>
                      <p><strong>Order ID:</strong> {{ $item->order_id }}</p>
                      <p><strong>Customer:</strong> {{ $item->customer_name }}</p>
                      <p><strong>Tracking #:</strong> {{ $item->tracking_number ?? 'N/A' }}</p>

                      <hr>

                      <h6>Scan History</h6>
                      <table class="table table-sm table-bordered">
                        <thead class="table-light">
                          <tr>
                            <th>#</th>
                            <th>Scanned By</th>
                            <th>Quantity</th>
                            <th>Scanned At</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($scanDetails[$item->item_id] ?? [] as $scan)
                          <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $scan->scanned_by }}</td>
                            <td>{{ $scan->quantity_scanned }}</td>
                            <td>{{ $scan->scanned_at }}</td>
                          </tr>
                          @endforeach

                          @if(empty($scanDetails[$item->item_id]))
                          <tr>
                            <td colspan="4" class="text-center text-muted">No scan data available.</td>
                          </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                  </div>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center">No items found for selected user.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {!! $items->appends(request()->query())->links('vendor.pagination.bootstrap-5') !!}
    </div>
  </div>
</div>
@endsection

