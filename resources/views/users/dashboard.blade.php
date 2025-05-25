@extends('layouts.admin')

@section('title', 'User Dashboard')

@section('content')

<div class="container-fluid py-4">

  <!-- 🔍 Filter by Date Range 
  <div class="row mb-4">
    <div class="col-md-4">
      <label for="user-date-range" class="form-label fw-bold">📅 Filter by Date Range</label>
      <input type="text" name="datetimes" id="user-date-range" class="form-control" placeholder="Select date range">
    </div>
  </div>-->

  <!-- 📊 Summary Cards -->
  <div class="row mb-4">
  <div class="col-md-3">
    <div class="info-box bg-primary">
      <span class="info-box-icon"><i class="fas fa-box"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Scans This Week</span>
        <span class="info-box-number">{{ $totalScansThisWeek }}</span>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="info-box bg-success">
      <span class="info-box-icon"><i class="fas fa-check"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Completed Scans</span>
        <span class="info-box-number">{{ $completedScans }}</span>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="info-box bg-warning">
      <span class="info-box-icon"><i class="fas fa-sync-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Scans</span>
        <span class="info-box-number">{{ $pendingScans }}</span>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="info-box bg-info">
      <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Last Scan Date</span>
        <span class="info-box-number">{{ $lastScanDate }}</span>
      </div>
    </div>
  </div>
</div>
  <!-- 1. Chart – My Daily Scans -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      <h3 class="card-title">📅 My Daily Scans</h3>
    </div>
    <div class="card-body">
      <canvas id="daily-scans-chart" height="100"></canvas>
    </div>
  </div>

  <!-- 2. Chart – Scan Status Overview -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white">
      <h3 class="card-title">✅ Scan Status Overview</h3>
    </div>
    <div class="card-body text-center">
      <div style="max-width: 300px; margin: auto;">
        <canvas id="scan-status-chart"></canvas>
      </div>
    </div>
  </div>

  <!-- 3. Line Chart – My Scanning Trend -->
  <div class="card mb-4">
    <div class="card-header bg-info text-white">
      <h3 class="card-title">📈 My Scanning Trend</h3>
    </div>
    <div class="card-body">
      <canvas id="scanning-trend-chart" height="100"></canvas>
    </div>
  </div>

  </div>

@endsection
