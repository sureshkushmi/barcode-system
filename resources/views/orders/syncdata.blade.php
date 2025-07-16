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
