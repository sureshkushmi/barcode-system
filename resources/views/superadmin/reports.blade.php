@extends('layouts.admin') {{-- Your AdminLTE master layout --}}
@section('title', 'User Scanning Report')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">User Scanning Report</h3>
      <form method="GET" action="{{ route('reports.user-scanning') }}" class="d-flex">
        <input type="text" name="datetimes" class="form-control me-2" placeholder="Select Date Range" value="{{ request('datetimes') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
      </form>
    </div>

    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead class="table-light">
          <tr>
            <th>Shipment Tracking #</th>
            <th>Total Qty</th>
            <th>Quantity Scanned</th>
            <th>Scanned At</th>
            <th>Status</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          @forelse($shipments as $shipment)
          <tr>
            <td>{{ $shipment->tracking_number }}</td>
            <td>{{ $shipment->total_qty }}</td>
            <td>{{ $shipment->scanned_qty }}</td>
            <td>{{ $shipment->scanned_at }}</td>
            <td>
              <span class="badge bg-{{ $shipment->status === 'Delivered' ? 'success' : ($shipment->status === 'Pending' ? 'warning' : 'secondary') }}">
                {{ $shipment->status }}
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $shipment->id }}">View</button>

              <!-- Modal -->
              <div class="modal fade" id="detailsModal{{ $shipment->id }}" tabindex="-1" aria-labelledby="detailsLabel{{ $shipment->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="detailsLabel{{ $shipment->id }}">Shipment Details #{{ $shipment->tracking_number }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p><strong>Tracking Number:</strong> {{ $shipment->tracking_number }}</p>
                      <p><strong>Total Quantity:</strong> {{ $shipment->total_qty }}</p>
                      <p><strong>Scanned Quantity:</strong> {{ $shipment->scanned_qty }}</p>
                      <p><strong>Status:</strong> {{ $shipment->status }}</p>
                      <p><strong>Scanned At:</strong> {{ $shipment->scanned_at }}</p>
                      <p><strong>Notes:</strong> {{ $shipment->notes ?? 'No notes available.' }}</p>
                      <!-- Add more info if needed -->
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
            <td colspan="6" class="text-center">No data found for selected date range.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {{ $shipments->withQueryString()->links() }}
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
  $(function () {
    $('input[name="datetimes"]').daterangepicker({
      timePicker: true,
      startDate: moment().subtract(7, 'days'),
      endDate: moment(),
      locale: {
        format: 'M/DD hh:mm A'
      }
    });
  });
</script>
@endpush
