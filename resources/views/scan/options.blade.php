@extends('layouts.admin')

@section('title', 'Choose Scanning Method')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card card-primary shadow-sm">
        <div class="card-header">
          <h3 class="card-title">Choose Scanning Method</h3>
        </div>

        <div class="card-body">
          <div class="mb-4">
            <h5 class="mb-3">
              <strong>Shipment:</strong> {{ $shipment->tracking_number }}
            </h5>

            @if ($kit)
              <div class="alert alert-success mb-4">
                ✅ Kit available: <strong>{{ $kit->barcode }}</strong>
              </div>

              <a href="{{ route('scan.kit', $kit->id) }}" class="btn btn-success mb-3">
                Scan Kit Barcode
              </a>
            @endif

            <div>
              <p class="mb-2">📦 Or scan items individually:</p>
              <a href="{{ route('scan.items', $shipment->id) }}" class="btn btn-primary">
                Scan Individual Items
              </a>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
