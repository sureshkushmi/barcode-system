@extends('layouts.admin')

@section('title', 'Scan Shipping Label')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-md-12">

        <div class="card card-primary shadow-sm">
          <div class="card-header">
            <h3 class="card-title">Scan Shipping Label</h3>
          </div>

          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger">
                {{ session('error') }}
              </div>
            @endif


            <form method="POST"  action="{{ route('scan.label') }}">
              @csrf
              
              <div class="form-group mb-3">
                <label for="tracking_number">Tracking Number</label>
                <input type="text" name="tracking_number" id="tracking_number" class="form-control" required>
              </div>

              <button type="submit" class="btn btn-primary">Fetch Items</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection
