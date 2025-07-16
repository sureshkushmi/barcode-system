@extends('layouts.admin')

@section('title', 'Scan Kit Barcode')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card card-primary shadow-sm">
        <div class="card-header">
          <h3 class="card-title">Scan Kit Barcode</h3>
        </div>

        <div class="card-body">

          {{-- Flash Messages --}}
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
          @endif

          {{-- Validation Errors --}}
          @if ($errors->any())
            <div class="alert alert-warning">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <p><strong>Kit Barcode:</strong> {{ $kit->barcode }}</p>
          <p><strong>Total Items:</strong> {{ $kit->items->count() }}</p>

          <form method="POST" action="{{ route('submit.kit.scan', $kit->id) }}" class="mt-4">
            @csrf

            <div class="form-group mb-3">
              <label for="barcode" class="form-label">Scan Kit Barcode</label>
              <input type="text" name="barcode" id="barcode" class="form-control" required autofocus>
            </div>

            <button type="submit" class="btn btn-success">
              Submit Scan
            </button>
          </form>
          <a href="{{ route('scan.label') }}" class="btn btn-secondary mt-3">🔄 Scan Another Label</a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
