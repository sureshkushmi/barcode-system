@extends('layouts.admin') {{-- This extends your admin.blade.php --}}

@section('title', 'User Management')

@section('content')
  <!--begin::App Main-->
  <div class="container-fluid">
  <!-- Chart 1: Bar Chart – Quantity Scanned by User -->
  <div class="card mb-4">
  <div class="card-header bg-primary text-white">
    <h3 class="card-title mb-0">Quantity Scanned by User</h3>
  </div>

  <div class="card-body">
    <div class="row g-3 align-items-end mb-4">
      <!-- Filter by Today / This Week -->
      <div class="col-md-3">
        <label for="scan-filter" class="form-label fw-bold">Filter</label>
        <select id="scan-filter" class="form-select">
          <option value="day">Today</option>
          <option value="week">This Week</option>
        </select>
      </div>

      <!-- 📅 Filter by Date Range -->
      <div class="col-md-4">
        <label for="user-date-range" class="form-label fw-bold">📅 Filter by Date Range</label>
        <input type="text" name="datetimes" id="user-date-range" class="form-control" placeholder="Select date range">
      </div>
    </div>

    <!-- Chart Area -->
    <canvas id="user-scan-chart" height="100"></canvas>
  </div>
</div>


  <div class="row">
  <!-- Chart 2: Scans by Status -->
  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h3 class="card-title">Scans by Status</h3>
      </div>
      <div class="card-body d-flex justify-content-center">
        <div style="max-width: 400px;">
          <canvas id="status-chart" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart 3: Scans Over Time -->
  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-header bg-info text-white">
        <h3 class="card-title">Scans Over Time</h3>
      </div>
      <div class="card-body">
        <canvas id="scan-trend-chart" height="170"></canvas>
      </div>
    </div>
  </div>
</div>



      <!--end::App Main-->
@endsection
