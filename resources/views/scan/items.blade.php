@extends('layouts.admin')

@section('title', 'Scan Items')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-md-10">

      {{-- Shipment Info and Scan Form --}}
      <div class="card card-primary shadow-sm mb-4">
        <div class="card-header">
          <h3 class="card-title">Scan Items</h3>
        </div>

        <div class="card-body">
          <h5 class="mb-3"><strong>Shipment:</strong> {{ $shipment->tracking_number }}</h5>

          {{-- Show Validation Errors --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Show Session Error --}}
          @if (session('error'))
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
          @endif

          {{-- Show Session Success --}}
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <form method="POST" action="{{ route('submit.item.scan', $shipment->id) }}">
            @csrf
            <div class="form-group mb-3">
              <label for="barcode" class="form-label">Scan Item Barcode</label>
              <input type="text" name="barcode" id="barcode" class="form-control" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary">Submit Scan</button>
          </form>
        </div>
      </div>

      {{-- Required Items List --}}
      <div class="card card-secondary shadow-sm">
        <div class="card-header">
          <h4 class="card-title">Required Items</h4>
        </div>

        <div class="card-body">
          <ul class="list-group list-group-flush">
            @foreach ($shipment->items as $item)
              <li class="list-group-item">
                {{ $item->name }} — 
                <strong>{{ $item->required_quantity }}</strong> pcs 
                (Scanned: {{ $item->completed }}) :- 
                Barcode ({{ $item->barcode }})
              </li>
            @endforeach
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
