@extends('layouts.admin')

@section('title', 'User Scanning Reports')

@section('content')
<div class="container-fluid py-4">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">User Scanning Reports</h3>
      </div>

      <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover text-nowrap">
          <thead>
            <tr>
              <th>Shipment Tracking #</th>
              <th>Item Name</th>
              <th>Quantity Scanned</th>
              <th>Scanned At</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($scans as $scan)
              <tr>
                <td>{{ $scan->shipment->tracking_number ?? 'N/A' }}</td>
                <td>{{ $scan->item->name ?? 'Label Scan' }}</td>
                <td>{{ $scan->quantity_scanned }}</td>
                <td>{{ $scan->scanned_at->format('Y-m-d H:i') }}</td>
                <td>{{ ucfirst($scan->status) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">No scans found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="card-footer clearfix">
        {{ $scans->links() }}
      </div>
    </div>

  </div>
@endsection
