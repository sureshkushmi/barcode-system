@extends('layouts.admin')

@section('title', 'User Dashboard')

@section('content')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-lg-12">

        <div class="card card-primary shadow-sm">
          <div class="card-header">
            <h3 class="card-title">Welcome, {{ $user->name }}</h3>
          </div>

          <div class="card-body">
            <p><strong>Email:</strong> {{ $user->email }}</p>

            <div class="mt-4">
              <a href="{{ route('scan.label') }}" class="btn btn-primary">
                📦 Start Scanning Shipping Label
              </a>

              <a href="{{ route('user.reports') }}" class="btn btn-success ml-3">
                📊 View My Reports
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection
