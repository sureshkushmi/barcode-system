@extends('layouts.admin') {{-- This extends your admin.blade.php --}}

@section('title', 'User Management')

@section('content')
  <div class="container-fluid py-4">
    <div class="card">
      <div class="card-header d-flex justify-between items-center">
        <h3 class="card-title font-weight-bold">User List</h3>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-success">
          <i class="bi bi-plus-lg"></i> Add User
        </a>
        
                <a href="{{ route('superadmin.users.export') }}" class="btn btn-outline-success ms-2" data-bs-toggle="tooltip" title="Export all users to Excel">
            <i class="bi bi-download"></i> Export Users
        </a>


      </div>

      <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
        <table class="table table-striped table-hover">
  <thead class="thead-light">
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th style="width: 300px;">Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role }}</td>
        <td>
               <a href="{{ route('superadmin.reports.relateduser-scanning', $user->id) }}" class="btn btn-sm btn-info">
                  <i class="bi bi-bar-chart-line-fill"></i> Scan Report
                </a>


          <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil-square"></i> Edit
          </a>
          <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
              <i class="bi bi-trash"></i> Delete
            </button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<div class="modal fade" id="scanReportModal" tabindex="-1" aria-labelledby="scanReportLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="scanReportLabel">User Scanning Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="scan-report-content">
        <div class="text-center">Loading...</div>
      </div>
    </div>
  </div>
</div>


<!-- Pagination -->
<div class="mt-3 d-flex justify-content-center">
    {!! $users->links('vendor.pagination.bootstrap-5') !!}
</div>

      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script>
  $(document).on('click', '.show-scan-report', function () {
    const userId = $(this).data('user-id');
    const baseUrl = "{{ url('/') }}";
    $('#scan-report-content').html('<div class="text-center">Loading...</div>');

    $.ajax({
      url: baseUrl + '/superadmin/user-scan-report/' + userId,
      method: 'GET',
      success: function (response) {
        if (response.success) {
            console.log("AJAX Response:", response);

          const data = response.data;
          let html = `
            <p><strong>Scans Today:</strong> ${data.scansToday}</p>
            <p><strong>Scans This Week:</strong> ${data.scansWeek}</p>
            <p><strong>Total Scans:</strong> ${data.scansTotal}</p>
            <hr>
            <h6>Recent Scan Details</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Barcode</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Scanned Qty</th>
                    <th>Scanned At</th>
                  </tr>
                </thead>
                <tbody>`;

          if (data.details.length === 0) {
            html += `<tr><td colspan="7" class="text-center text-muted">No scan history found.</td></tr>`;
          } else {
            data.details.forEach((item, index) => {
              html += `
                <tr>
                  <td>${index + 1}</td>
                  <td>${item.item_name}</td>
                  <td>${item.barcode}</td>
                  <td>${item.shippingeasy_order_id}</td>
                  <td>${item.customer_name}</td>
                  <td>${item.quantity_scanned}</td>
                  <td>${item.scanned_at}</td>
                </tr>`;
            });
          }

          html += `
                </tbody>
              </table>
            </div>
          `;

          $('#scan-report-content').html(html);
        } else {
          $('#scan-report-content').html('<p class="text-danger">Failed to load scan report.</p>');
        }
      },
      error: function () {
        $('#scan-report-content').html('<p class="text-danger">Error loading scan report.</p>');
      }
    });
  });
</script>

@endpush
