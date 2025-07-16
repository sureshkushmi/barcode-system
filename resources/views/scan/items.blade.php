@php use Illuminate\Support\Str; @endphp

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

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
          @endif

          @if (session('error')) 
            <div class="alert alert-danger">{{ session('error') }}</div> 
          @endif
          @if (session('success')) 
            <div class="alert alert-success">{{ session('success') }}</div> 
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

      {{-- Required Items Table --}}
      <div class="card card-secondary shadow-sm">
        <div class="card-header">
          <h4 class="card-title">Required Items</h4>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead class="table-light">
              <tr>
                <th>Order Quantity</th>
                <th>Required Qty</th>
                <th>Scanned Qty</th>
                <th>Barcode</th>
                <th>Item Name</th>
                
                
              </tr>
            </thead>
            <tbody>
              @forelse ($shipment->items as $item)
                <tr>
                  <td>{{ $item->quantity }}</td>
                  <td>{{ $item->required_quantity }}</td>
                     @php
                      $scannedQty = \App\Models\Scan::where('item_id', $item->id)->sum('quantity_scanned');
                     @endphp
                    <td>{{ $scannedQty }}</td>
                  <td>{{ $item->barcode }}</td>
                  <td>{{ Str::limit($item->name, 60) }}</td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">No items found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
