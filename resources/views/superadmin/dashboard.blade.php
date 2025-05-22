@extends('layouts.admin') {{-- This extends your admin.blade.php --}}

@section('title', 'User Management')

@section('content')
  <!--begin::App Main-->
  <div class="container-fluid">
  <!-- Chart 1: Bar Chart – Quantity Scanned by User -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      <h3 class="card-title">Quantity Scanned by User</h3>
    </div>
    <div class="d-flex flex-wrap align-items-center mb-4 p-3">
      <div class="me-3" style="min-width: 200px;">
        <select id="scan-filter" class="form-select">
          <option value="day">Today</option>
          <option value="week">This Week</option>
          <option value="range">Custom Range</option>
        </select>
      </div>
      <div id="range-container" class="d-none" style="min-width: 250px;">
        <input type="text" id="scan-date-range" class="form-control" placeholder="Select date range">
      </div>
    </div>
    <div class="card-body">
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
