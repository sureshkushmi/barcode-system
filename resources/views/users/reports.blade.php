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
        <input 
          type="text" 
          name="datetimes" 
          class="form-control me-2" 
          placeholder="Select Date Range" 
          value="{{ request('datetimes') }}"
          autocomplete="off"
        >
        <button type="submit" class="btn btn-primary">Filter</button>
      </form>

      <a href="{{ route('reports.relatedUser-scanning.export', ['datetimes' => request('datetimes')]) }}" class="btn btn-outline-success ms-2">
        <i class="bi bi-download"></i> Export Report
      </a>
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
            <td>{{ $shipment->scanned_at ?? 'N/A' }}</td>
            <td>
              <span class="badge bg-{{ strtolower($shipment->status) === 'delivered' ? 'success' : (strtolower($shipment->status) === 'pending' ? 'warning' : 'secondary') }}">
                {{ ucfirst($shipment->status) }}
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $shipment->id }}">View</button>

              <!-- Modal -->
              <div class="modal fade" id="detailsModal{{ $shipment->id }}" tabindex="-1" aria-labelledby="detailsLabel{{ $shipment->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                      <h5 class="modal-title" id="detailsLabel{{ $shipment->id }}">Shipment Details</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      <div class="mb-3">
                        <strong>Status:</strong> 
                        <span class="badge bg-{{ strtolower($shipment->status) === 'pending' ? 'warning' : (strtolower($shipment->status) === 'delivered' ? 'success' : 'secondary') }}">
                          {{ ucfirst($shipment->status) }}
                        </span>
                      </div>

                      <div class="row mb-3">
                        <div class="col-md-12"><strong>Tracking Number:</strong> {{ $shipment->tracking_number }}</div>
                        <div class="col-md-12"><strong>Scan Date:</strong> {{ $shipment->scanned_at ?? 'N/A' }}</div>
                        <div class="col-md-12"><strong>Scan By:</strong> {{ $scannedItems[$shipment->id]->first()->scanned_by ?? 'Unknown' }}</div>
                      </div>

                      <hr>

                      <h6>Scanned Items:</h6>

                      <table class="table table-sm table-bordered table-hover">
                        <thead class="table-light">
                          <tr>
                            <th style="width: 5%;">#</th>
                            <th>Item Name</th>
                            <th style="width: 15%;">Total Qty</th>
                            <th style="width: 15%;">Scanned Qty</th>
                            <th style="width: 20%;">Barcode</th>
                            <th>Notes</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php $counter = 1; @endphp
                          @foreach($scannedItems[$shipment->id] ?? [] as $item)
                          <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->total_quantity }}</td>
                            <td>{{ $item->quantity_scanned }}</td>
                            <td>{{ $item->barcode }}</td>
                            <td>{{ $item->notes ?? '-' }}</td>
                          </tr>
                          @endforeach

                          @if(empty($scannedItems[$shipment->id]) || count($scannedItems[$shipment->id]) === 0)
                          <tr>
                            <td colspan="6" class="text-center text-muted">No scanned items available.</td>
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
            <td colspan="6" class="text-center">No data found for selected date range.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {!! $shipments->withQueryString()->links('vendor.pagination.bootstrap-5') !!}
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
